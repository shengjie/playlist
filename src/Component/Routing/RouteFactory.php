<?php

namespace Component\Routing;

class RouteFactory {

  public static function createFromConfig(string $routeName, array $routeConfig) {
    $route = new Route();
    $route->name = $routeName;
    $route->method = $routeConfig['method'] ?? 'ALL';
    $route->path = $routeConfig['path'];
    $route->action = $routeConfig['action'];
    return $route;
  }
}
