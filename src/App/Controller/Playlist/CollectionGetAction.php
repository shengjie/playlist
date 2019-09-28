<?php

namespace App\Controller\Playlist;

use Component\Http\JsonResponse;
use Component\Http\Request;
use Component\Routing\Router;
use App\Repository\PlaylistRepository;

class CollectionGetAction {

  /**
   * @var PlaylistRepository
   */
  private $playlistRepository;

  public function __construct(PlaylistRepository $playlistRepository) {
    $this->playlistRepository = $playlistRepository;
  }

  public function __invoke(Request $request) {
    $playlists = $this->playlistRepository->fetchAll();
    return [
      'data' => $playlists
    ];
  }
}
