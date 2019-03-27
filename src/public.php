<?php
declare(strict_types=1);
require __DIR__ . "/vendor/autoload.php";
require __DIR__ . "/bootstrap.php";

use Slim\Http\Request;
use Slim\Http\Response;

use UCRM\HTTP\Slim\Controllers\Common;

use App\Settings;
use App\Controllers;

/**
 * Use an immediately invoked function here, to avoid global namespace pollution...
 *
 * @author Ryan Spaeth <rspaeth@mvqn.net>
 *
 */
(function() use ($app, $container)
{
    // =================================================================================================================
    // CONFIGURATION
    // =================================================================================================================

    // NOTE: The following must contain valid paths if their corresponding Controllers are to function properly!

    // Defines the absolute path to use when looking up static assets, including PHP scripts!
    define("ASSET_PATH", realpath(__DIR__."/public/"));

    // Defines the absolute path to use when looking up templates!
    define("VIEWS_PATH", realpath(__DIR__."/src/App/Views/"));

    // NOTE: The following define is only available in PHP scripts!
    define("BASE_URL", isset($_SERVER["HTTP_REFERER"]) ?
        rtrim(Settings::PLUGIN_PUBLIC_URL, ".php") :    // .../public
        Settings::PLUGIN_PUBLIC_URL."?");               // .../public.php?

    // TODO: Add any additional configuration you need here...

    // =================================================================================================================
    // CUSTOM ROUTES
    // =================================================================================================================

    // Example routing using a Controller...
    new Controllers\ExampleController($app);

    // Custom route using an inline method...
    // NOTE: Notice this more specific route takes precedence over the previous /example route.
    $app->get("/example/{name}",

        function ( /** @noinspection PhpUnusedParameterInspection */ Request $request, Response $response, array $args)
            use ($container)
        {
            return $response->withJson([ "name" => $args["name"], "description" => "This is an example JSON route!" ]);
        }

    );

    // NOTE: You can include any valid route syntax supported by the Slim Framework.  All routes and controllers placed
    // here will override any built-in controllers added below.  This is the perfect location to place API (server-side)
    // routes for consumption by the client-side code provided by VueJS in this Plugin.

    // TODO: Add additional custom routes or controllers here!
    // ...

    // =================================================================================================================
    // BUILT-IN ROUTES
    // Note: These controllers should be added last, so the above controllers can override routes as needed.
    // =================================================================================================================

    // Append a route handler for static assets.
    new Common\AssetController($app);
    // NOTE: This will load static assets (i.e. png, jpg, html, pdf, etc.)

    // Append a route handler for Twig templates.
    new Common\TemplateController($app);

    // Append a route handler for PHP scripts.
    new Common\ScriptController($app);

    // =================================================================================================================
    // APPLICATION EXECUTION
    // =================================================================================================================

    // Run the Slim Framework Application!
    $app->run();

})();

