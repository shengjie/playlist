<?php
include __DIR__.'/../vendor/autoload.php';

use Component\Http\RequestFactory;
use Component\Kernel;
use Component\DependencyInjection\Container;

$kernel = new Kernel(
  // define classes into service container
  [
    \App\Controller\IndexAction::class => true, // true == use autowire
    \App\Controller\Playlist\CollectionGetAction::class => true,
    \App\Controller\Playlist\ItemGetAction::class => true,
    \App\Controller\Playlist\ItemPostAction::class => true,
    \App\Controller\Playlist\ItemDeleteAction::class => true,
    \App\Controller\Playlist\ItemVideoCollectionGetAction::class => true,
    \App\Controller\Playlist\ItemVideoCollectionPostAction::class => true,
    \App\Controller\Playlist\ItemVideoItemDeleteAction::class => true,
    \App\Controller\Video\CollectionGetAction::class => true,
    \App\Repository\VideoRepository::class => true,
    \App\Repository\PlaylistRepository::class => true,
    \App\Hateoas\PlaylistHateoasResolver::class => true,
    \Component\Routing\Router::class => static function(Container $container) {
      return new \Component\Routing\Router($container->getSetting('routing'));
    },
    'router' => \Component\Routing\Router::class,
    \Component\Persistence\ConnectionFactory::class => static function(Container $container) {
      return new \Component\Persistence\ConnectionFactory($container->getSetting('database'));
    },
    \Component\Http\JsonResponseFactory::class => true,
    'response_factory' => \Component\Http\JsonResponseFactory::class,
  ],
  // load settings from external configuration file
  json_decode(file_get_contents(__DIR__.'/../config/settings.json'), true)
);
$request = RequestFactory::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
