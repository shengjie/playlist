<?php

namespace Component;

use Component\Http\Request;
use Component\Http\ResponseFactory;
use Component\DependencyInjection\Container;

class Kernel {
  protected $container;

  public function __construct(array $definitions, array $settings) {
    $this->container = new Container($definitions, $settings);
  }

  public function handle(Request $request) {
    $router = $this->container->get('router');
    try {
      $route = $router->match($request);
      $action = $this->container->get($route->action);
      $actionResult = $action->__invoke($request);
      return $this->generateResponse($request, $actionResult);
    } catch(\Exception $e) {
      $responseFactory = $this->container->get('response_factory');
      \error_log((string) $e, 0);
      return $responseFactory->createFromException($request, $e);
    }
  }

  protected function generateResponse(Request $request, array $actionResult) {
    $responseFactory = $this->container->get('response_factory');
    $response = $responseFactory->createFromActionResult($request, $actionResult);
    return $response;
  }
}
