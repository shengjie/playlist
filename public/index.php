<?php
include __DIR__.'/../vendor/autoload.php';

use App\RequestFactory;
use App\Kernel;

$kernel = new Kernel();
$request = RequestFactory::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
