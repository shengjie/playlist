<?php

namespace Component\Routing;

use Component\Http\Request;

/**
* Router is responsible for analysis request information and returns the correct route
*/
class Router {

  /**
  * @var Route[]
  */
  private $routes;

  /**
   * @var array
   */
  private $routeTable;

  private static function resolvePath(string $path) {
    return array_map(
      static function(string $part) {
        if ($part[0] === '{' && $part[-1] === '}') {
          return '*';
        }
        return $part;
      },
      explode('/', $path)
    );
  }

  public function __construct(array $routeConfigs) {
    $this->routeTable = [];

    foreach($routeConfigs as $routeName => $routeConfig) {
      $route = new Route();
      $route->name = $routeName;
      $route->method = $routeConfig['method'] ?? 'ALL';
      $route->path = $routeConfig['path'];
      $route->action = $routeConfig['action'];
      $this->routes[$routeName] = $route;
    }

    $this->buildRouteTable();
  }

  public function match(Request $request) {
    $parts = self::resolvePath($request->method, $request->pathInfo);
    $args = [];
    $route = $this->resolve($this->routeTable, $parts, $args);
    if (null === $route) {
      throw new \RuntimeException('No route match for request');
    }
    return $route;
  }

  private function buildRouteTable() {
    foreach($this->routes as $route) {
      $parts = self::resolvePath($route->method . $route->path);
      $t = &$this->routeTable;
      foreach($parts as $part) {
        if (!isset($t[$part])) {
          $t[$part] = [];
        }
        $t = &$t[$part];
      }
      $t['#'] = $route->name;
      unset($t);
    }
  }

  private function resolve(array $table, array $parts, array &$args) {
    if (empty($parts) && isset($table['#'])) {
      return $this->routes[$table['#']];
    }

    $part = array_shift($parts);
    if (isset($table[$part])) {
      return $this->resolve($table[$part], $parts, $args);
    }

    if (isset($table['*'])) {
      $args[] = $part;
      return $this->resolve($table['*'], $parts, $args);
    }

    return null;
  }
}
