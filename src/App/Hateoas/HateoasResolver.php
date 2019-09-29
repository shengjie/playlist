<?php

namespace App\Hateoas;

use Component\Routing\Router;
use Component\Http\Request;
use App\Dto\Playlist;
use App\Dto\Video;

class HateoasResolver {

  /**
   * @var Router
   */
  private $router;

  public function __construct(Router $router) {
    $this->router = $router;
  }

  public function generateForPlaylist(Playlist $playlist) {
    return [
      ['rel' => 'self', 'href' => $this->router->generate('app_playlist_item_get', ['id' => $playlist->id]), 'type' => 'GET'],
      ['rel' => 'edit', 'href' => $this->router->generate('app_playlist_item_put', ['id' => $playlist->id]), 'type' => 'PUT'],
      ['rel' => 'remove', 'href' => $this->router->generate('app_playlist_item_delete', ['id' => $playlist->id]), 'type' => 'DELETE'],
      ['rel' => 'list', 'href' => $this->router->generate('app_playlist_item_video_collection_get', ['id' => $playlist->id]), 'type' => 'GET'],
    ];
  }

  public function generateForVideo(Video $playlist) {
    return [
      ['rel' => 'self', 'href' => $this->router->generate('app_video_item_get', ['id' => $playlist->id]), 'type' => 'GET'],
    ];
  }

  public function resolveActionResult(Request $request, array $actionResult) {
    if (is_object($actionResult['data'])) {
      $this->configureObject($actionResult['data']);
    }

    if (is_array($actionResult['data'])) {
      array_walk($actionResult['data'], [$this, 'configureObject']);
    }

    $id = $request->attributes['id'];

    $actionResult['_links'] = [];
    if (0 === strpos($request->attributes['_route'], 'app_video_collection_')) {
      $actionResult['_links'][] = ['rel' => 'list', 'href' => $this->router->generate('app_video_collection_get', ['id' => $id]), 'type' => 'GET'];
    }

    if (0 === strpos($request->attributes['_route'], 'app_playlist_collection_')) {
      $actionResult['_links'][] = ['rel' => 'list', 'href' => $this->router->generate('app_playlist_collection_get', ['id' => $id]), 'type' => 'GET'];
      $actionResult['_links'][] = ['rel' => 'edit', 'href' => $this->router->generate('app_playlist_collection_post', []), 'type' => 'POST'];
    }

    if (0 === strpos($request->attributes['_route'], 'app_playlist_item_video_collection_')) {
      $actionResult['_links'][] = ['rel' => 'list', 'href' => $this->router->generate('app_playlist_item_video_collection_get', ['id' => $id]), 'type' => 'GET'];
      $actionResult['_links'][] = ['rel' => 'edit', 'href' => $this->router->generate('app_playlist_item_video_collection_post', ['id' => $id]), 'type' => 'POST'];
    }

    if (0 === strpos($request->attributes['_route'], 'app_playlist_item_video_collection_get')) {
      foreach($actionResult['data'] as $video) {
        $video->_links[] = ['rel' => 'remove', 'href' => $this->router->generate('app_playlist_item_video_item_delete', ['id' => $id, 'vid' => $video->id]), 'type' => 'DELETE'];
      }
    }

    return $actionResult;
  }

  protected function configureObject($object) {
    if ($object instanceof Playlist) {
      $object->_links = $this->generateForPlaylist($object);
    }
    if ($object instanceof Video) {
      $object->_links = $this->generateForVideo($object);
    }
  }
}
