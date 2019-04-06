<?php
declare(strict_types=1);

$path = "/data/ucrm/data/plugins/test.log";

try
{

require_once __DIR__."/vendor/autoload.php";
require_once __DIR__."/bootstrap.php";

//use UCRM\Common\Plugin;
//use UCRM\Common\Log;

//Plugin::initialize(__DIR__);

//Log::info("Install Hook!");



    $cwd = getcwd();

    file_put_contents($path, "Installed:\n", LOCK_EX | FILE_APPEND);
    file_put_contents($path, "getcwd(): ($cwd)\n", LOCK_EX | FILE_APPEND);
    file_put_contents($path, "ucrm.json: \n".file_get_contents(__DIR__."/ucrm.json")."\n", LOCK_EX | FILE_APPEND);

}
catch(Exception $e)
{
    file_put_contents($path, "Installed:\n{$e->getMessage()}", LOCK_EX | FILE_APPEND);
}
