<?php

namespace Component;

use Component\Http\Request;
use Component\Http\ResponseFactory;
use Component\DependencyInjection\Container;

class Kernel {
  private $container;

  public function __construct(array $definitions, array $settings) {
    $this->container = new Container($definitions, $settings);
  }

  public function handle(Request $request) {
    $router = $this->container->get('router');
    $responseFactory = $this->container->get('response_factory');
    try {
      $route = $router->match($request);
      $action = $this->container->get($route->action);
      $actionResult = $action->__invoke($request);
      $response = $responseFactory->createFromActionResult($request, $actionResult);
      return $response;
    } catch(\Exception $e) {
      \error_log((string) $e, 0);
      return $responseFactory->createFromException($request, $e);
    }
  }
}
