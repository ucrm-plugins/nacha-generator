<?php
declare(strict_types=1);

//require_once __DIR__."/vendor/autoload.php";
//require_once __DIR__."/bootstrap.php";

//use UCRM\Common\Plugin;
//use UCRM\Common\Log;

//Plugin::initialize(__DIR__);

//Log::info("Install Hook!");


$path = "/data/ucrm/data/plugins/test.log";

file_put_contents($path, "Configured\n", LOCK_EX | FILE_APPEND);
