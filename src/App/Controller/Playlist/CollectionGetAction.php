<?php

namespace App\Controller\Playlist;

use Component\Http\JsonResponse;
use Component\Http\Request;
use Component\Routing\Router;
use App\Repository\PlaylistRepository;
use App\Hateoas\PlaylistHateoasResolver;

class CollectionGetAction {

  /**
   * @var PlaylistRepository
   */
  private $playlistRepository;

  /**
   * @var PlaylistHateoasResolver
   */
  private $playlistHateoasResolver;

  public function __construct(PlaylistRepository $playlistRepository, PlaylistHateoasResolver $playlistHateoasResolver) {
    $this->playlistRepository = $playlistRepository;
    $this->playlistHateoasResolver = $playlistHateoasResolver;
  }

  public function __invoke(Request $request) {
    $playlists = $this->playlistRepository->fetchAll();

    foreach($playlists as $playlist) {
      $this->playlistHateoasResolver->resolveItem($playlist);
    }

    return [
      'data' => $playlists
    ];
  }
}
