<?php

namespace App\Controller;

use Component\Http\Request;
use Component\Http\JsonResponse;
use Component\Routing\Router;

class IndexAction {

  /**
   * @var Router
   */
  private $router;

  public function __construct(Router $router) {
    $this->router = $router;
  }

  public function __invoke(Request $request) {
    return [
      'version' => '1.0',
      'links' => [
        [
          'rel' => 'list',
          'href' => $this->router->generate('app_playlist_collection_get')
        ],
        [
          'rel' => 'list',
          'href' => $this->router->generate('app_video_collection_get')
        ],
      ]
    ];
  }
}
