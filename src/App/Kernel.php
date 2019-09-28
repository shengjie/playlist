<?php

namespace App;

use Component\DependencyInjection\Container;
use Component\Http\Request;

class Kernel extends \Component\Kernel {
  public function __construct() {
    parent::__construct(
      // define classes into service container
      [
        \App\Controller\IndexAction::class => true, // true == use autowire
        \App\Controller\Playlist\CollectionGetAction::class => true,
        \App\Controller\Playlist\CollectionPostAction::class => true,
        \App\Controller\Playlist\ItemGetAction::class => true,
        \App\Controller\Playlist\ItemPutAction::class => true,
        \App\Controller\Playlist\ItemDeleteAction::class => true,
        \App\Controller\Playlist\ItemVideoCollectionGetAction::class => true,
        \App\Controller\Playlist\ItemVideoCollectionPostAction::class => true,
        \App\Controller\Playlist\ItemVideoItemDeleteAction::class => true,
        \App\Controller\Video\CollectionGetAction::class => true,
        \App\Controller\Video\ItemGetAction::class => true,
        \App\Repository\VideoRepository::class => true,
        \App\Repository\PlaylistRepository::class => true,
        \App\Hateoas\HateoasResolver::class => true,
        \Component\Routing\Router::class => [
          'arguments' => [
            json_decode(file_get_contents(__DIR__.'/../../config/routing.json'), true)
          ]
        ],
        'router' => \Component\Routing\Router::class,
        \Component\Persistence\ConnectionFactory::class => [
          'arguments' => [
            json_decode(file_get_contents(__DIR__.'/../../config/database.json'), true)
          ]
        ],
        \Component\Http\JsonResponseFactory::class => true,
        'response_factory' => \Component\Http\JsonResponseFactory::class
      ],
      []
    );
  }

  protected function generateResponse(Request $request, array $actionResult) {
    $hateoasResolver = $this->container->get(\App\Hateoas\HateoasResolver::class);
    $actionResult = $hateoasResolver->resolveActionResult($request, $actionResult);
    return parent::generateResponse($request, $actionResult);
  }
}
