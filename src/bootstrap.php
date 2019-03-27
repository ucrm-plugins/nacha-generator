<?php
declare(strict_types=1);
require_once __DIR__."/vendor/autoload.php";

// NOTE: This code block is not necessary, but makes VueJS URLs look a little friendlier, in my opinion!
// 'public.php#/' => 'public.php?/#/'
if(isset($_SERVER) && preg_match("#/public\.php$#", $_SERVER["REQUEST_URI"]) === 1)
{
    header("Location: public.php?/");
    exit();
}

use MVQN\Localization\Translator;
use MVQN\Localization\Exceptions\TranslatorException;
use MVQN\REST\RestClient;
use MVQN\Twig\Extensions\SwitchExtension;

use UCRM\Common\Config;
use UCRM\Common\Log;
use UCRM\Common\Plugin;
use UCRM\HTTP\Twig\Extensions\PluginExtension;
use UCRM\HTTP\Slim\Middleware\QueryStringRouter;

use App\Settings;

use Slim\Container;
use Slim\Http\Environment;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\Uri;
use Slim\Views\TwigExtension;

/**
 * bootstrap.php
 *
 * A common configuration and initialization file for all UCRM Plugins.
 *
 * @author Ryan Spaeth <rspaeth@mvqn.net>
 */

// =====================================================================================================================
// PLUGIN ECOSYSTEM
// =====================================================================================================================

// Initialize the Plugin libraries using this directory as the plugin root!
/** @noinspection PhpUnhandledExceptionInspection */
Plugin::initialize(__DIR__);

// Regenerate the Settings class, in case anything has changed in the manifest.json file.
/** @noinspection PhpUnhandledExceptionInspection */
Plugin::createSettings("App", "Settings");

// =====================================================================================================================
// ENVIRONMENT
// =====================================================================================================================

// IF an .env file exists in the project, THEN load it!
if(file_exists(__DIR__."/../.env"))
    (new \Dotenv\Dotenv(__DIR__."/../"))->load();

// =====================================================================================================================
// REST CLIENT
// =====================================================================================================================

// Generate the REST API URL from either an ENV variable (including from .env file), or fallback to localhost.
$restUrl =
    rtrim(
        getenv("REST_URL") ?:                                                           // .env (or ENV variable)
        (isset($_SERVER['HTTPS']) ? "https://localhost/" : "http://localhost/"),        // By initial request
    "/")."/api/v1.0";

// Configure the REST Client...
/** @noinspection PhpUnhandledExceptionInspection */
RestClient::setBaseUrl($restUrl);
RestClient::setHeaders([
    "Content-Type: application/json",
    "X-Auth-App-Key: " . Settings::PLUGIN_APP_KEY
]);

// Attempt to test the connection to the UCRM's REST API...
try
{
    // Get the Version from the REST API and log the information to the Plugin's logs.
    Log::info("Using REST URL: '".$restUrl."'");
    $version = \UCRM\REST\Endpoints\Version::get();
    Log::info("REST API Test : '".$version."'");
}
catch(\Exception $e)
{
    // We should have resolved all existing conditions that previously prevented successful connections!
    /** @noinspection PhpUnhandledExceptionInspection */
    Log::error($e->getMessage());
}

// =====================================================================================================================
// LOCALIZATION
// =====================================================================================================================

// Attempt to set the dictionary directory and "default" locale...
try
{
    Translator::setDictionaryDirectory(__DIR__ . "/translations/");
    /** @noinspection PhpUnhandledExceptionInspection */
    Translator::setCurrentLocale(str_replace("_", "-", Config::getLanguage()) ?: "en-US", true);
}
catch (TranslatorException $e)
{
    // TODO: Determine if we should simply fallback to "en-US" in the case of failure.
    Log::http("No dictionary could be found!", 500);
}

// =====================================================================================================================
// ROUTING (SLIM)
// =====================================================================================================================

// Create Slim Framework Application, given the provided settings.
$app = new \Slim\App([
    "settings" => [
        "displayErrorDetails" => true,
        "addContentLengthHeader" => false,
        "determineRouteBeforeAppMiddleware" => true,
    ],
]);

// =====================================================================================================================
// DEPENDENCY INJECTION
// =====================================================================================================================

// Get a reference to the DI Container included with the Slim Framework.
$container = $app->getContainer();

// =====================================================================================================================
// RENDERING (TWIG)
// =====================================================================================================================

// Configure the Slim/Twig Renderer...
$container["twig"] = function (Container $container)
{
    // Create a new instance of the Twig template renderer and configure the default options...
    $twig = new \Slim\Views\Twig(
        [
            __DIR__ . "/src/App/Views/",
        ],
        [
            //'cache' => 'path/to/cache'
            "debug" => true,
        ]
    );

    // Get the current Slim Router and initialize some defaults from the Environment.
    /** @var \Slim\Router $router */
    $router = $container->get("router");
    $uri = Uri::createFromEnvironment(new Environment($_SERVER));

    // Now add the standard TwigExtension to the specialized Slim/Twig view system.
    $twig->addExtension(new TwigExtension($router, $uri));

    // TODO: Determine if there is a replacement to this deprecated extension!
    /** @noinspection PhpDeprecationInspection */
    $twig->addExtension(new Twig_Extension_Debug());

    // Add our custom SwitchExtension for using {% switch/case/default %} tokens in the Twig templates.
    $twig->addExtension(new SwitchExtension());

    // Add our custom PluginExtension for using some Plugin-specific globals, functions and filters.
    $twig->addExtension(new PluginExtension(Settings::class));

    // Finally, return the newly configured Slim/Twig Renderer.
    return $twig;
};

// ---------------------------------------------------------------------------------------------------------------------

// Override the default 404 page...
$container['notFoundHandler'] = function (Container $container)
{
    return function(Request $request, Response $response) use ($container): Response
    {
        // Get the current Slim Router.
        /** @var \Slim\Router $router */
        $router = $container->get("router");

        // Setup some debugging information to pass along to the template...
        $data = [
            "vRoute" => $request->getAttribute("vRoute"),
            "router" => $router,
        ];

        // Finally, render our custom 404 page!
        /** @noinspection PhpUndefinedFieldInspection */
        return $container->twig->render($response,"404.html.twig", $data);
    };
};

// =====================================================================================================================
// LOGGING (MONOLOG)
// =====================================================================================================================

// TODO: Begin to convert our existing logging system to be more accommodating for the MonoLog system.
// Configure MonoLog...
$container['logger'] = function (/** @noinspection PhpUnusedParameterInspection */ \Slim\Container $container)
{
    // Create a new Logger with a context for this Plugin and some defaults...
    $logger = new Monolog\Logger("plugin");
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler(
        PHP_SAPI === "cli-server" ? "php://stdout" : __DIR__ . "/logs/www.log", \Monolog\Logger::DEBUG
    ));

    // Finally, return the new Logger.
    return $logger;
};

// =====================================================================================================================
// MIDDLEWARE (SLIM)
// =====================================================================================================================

// NOTE: Middleware is handled in ascending order, starting with the last middleware added!

// Handle Plugin-wide Authentication suing our custom PluginAuthentication middleware...
$app->add(new \UCRM\HTTP\Slim\Middleware\PluginAuthentication($container,
    function(\UCRM\Sessions\SessionUser $user)
    {
        // NOTE: Apply your own logic here and return TRUE/FALSE to authenticate successfully/unsuccessfully!
        return ($user->getUserGroup() === "Admin Group");
    }
));

// Use our custom QueryStringRouter middleware to route our Plugin URLs, setting the default URL...
$app->add(new QueryStringRouter("/index.html"));

/**
 * WARNING: The above QueryStringRouter middleware bypasses the current restrictions that UCRM places on Plugins with
 * multiple pages and should be used at he developer's discretion!
 *
 * The QueryStringRouter handles URLs as follows:
 * - The Plugin's 'public.php' script acts as a front-controller that parses the query-string for the requested URL.
 *
 *
 * EXAMPLE URLs:
 * - /public.php                                            => Loads the default route, as configured above.
 * - /public.php?/                                          => Same as the previous.
 * - /public.php?/index.html                                => Same as the previous, fully qualified URL.
 * - /public.php?/index.html&param1=1&param2=two            => Same as the previous, including some GET parameters.
 *
 * - /public.php?[route][&param=value...]                   => All other suffixes are handled by Slim Framework routes.
 *
 * - /public.php?/#/                                        => Our VueJS 'index.html' page and using vue-router syntax.
 *
 *
 * Visit https://github.com/mvqn/ucrm-plugin-sdk for additional information on my extended UCRM SDK.
 */