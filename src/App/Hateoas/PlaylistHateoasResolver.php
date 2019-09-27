<?php

namespace App\Hateoas;

use Component\Routing\Router;
use App\Dto\Playlist;

class PlaylistHateoasResolver {

  /**
   * @var Router
   */
  private $router;

  public function __construct(Router $router) {
    $this->router = $router;
  }

  public function resolveItem(Playlist $playlist) {
    $playlist->links = [
      ['rel' => 'self', 'href' => $this->router->generate('app_playlist_item_get', ['id' => $playlist->id])],
      ['rel' => 'list', 'href' => $this->router->generate('app_playlist_item_video_collection_get', ['id' => $playlist->id])]
    ];
  }
}
