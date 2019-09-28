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
      $this->routes[$routeName] = RouteFactory::createFromConfig($routeName, $routeConfig);
    }

    $this->buildRouteTable();
  }

  public function match(Request $request) {
    $reqParts = explode('/', $request->method . $request->pathInfo);
    $route = $this->resolve($this->routeTable, $reqParts);
    if (null === $route) {
      throw new Exceptions\NoRouteFoundException('Not found', 404);
    }

    $request->attributes['_route'] = $route->name;
    $args = \array_combine(explode('/', $route->method . $route->path), $reqParts);
    foreach ($args as $argK => $argV) {
      if ($argK[0] === '{' && $argK[-1] === '}') {
        $request->attributes[substr($argK, 1, -1)] = $argV;
      }
    }

    return $route;
  }

  public function generate(string $routeName, array $args = []) {
    $route = $this->routes[$routeName];

    $replaceArgs = [];
    foreach($args as $argK => $argV) {
      $replaceArgs['{'.$argK.'}'] = $argV;
    }

    return strtr($route->path, $replaceArgs);
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

  private function resolve(array $table, array $reqParts) {
    if (empty($reqParts)) {
      if (isset($table['#'])) {
        return $this->routes[$table['#']];
      }
    } else {
      $reqPart = array_shift($reqParts);
      if (isset($table[$reqPart])) {
        return $this->resolve($table[$reqPart], $reqParts);
      }

      if (isset($table['*'])) {
        return $this->resolve($table['*'], $reqParts);
      }
    }

    return null;
  }
}
