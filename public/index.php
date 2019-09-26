<?php
include __DIR__.'/../vendor/autoload.php';

use Component\Http\RequestFactory;
use App\Kernel;

$kernel = new Kernel(
  json_decode(file_get_contents(__DIR__.'/../config/settings.json'), true)
);
$request = RequestFactory::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
