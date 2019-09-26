<?php

namespace App;

use Component\Http\Request;
use Component\Http\ResponseFactory;
use Component\DependencyInjection\Container;

class Kernel {
  private $container;

  public function __construct(array $settings) {
    $this->container = new Container([
      \App\Controller\IndexAction::class => true,
      \App\Controller\Playlist\CollectionGetAction::class => true,
      \Component\Routing\Router::class => static function(Container $container) {
        return new \Component\Routing\Router($container->getSetting('routing'));
      },
      'router' => \Component\Routing\Router::class,
      \Component\Persistence\ConnectionFactory::class => static function(Container $container) {
        return new \Component\Persistence\ConnectionFactory($container->getSetting('database'));
      },
    ], $settings);
  }

  public function handle(Request $request) {
    $router = $this->container->get('router');
    $route = $router->match($request);
    $action = $this->container->get($route->action);
    $response = $action->__invoke($request);
    return $response;
  }
}
