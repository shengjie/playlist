<?php

namespace App\Controller\Playlist;

use Component\Http\JsonResponse;
use Component\Http\Request;
use Component\Http\Exceptions\HttpException;
use App\Repository\VideoRepository;
use App\Repository\PlaylistRepository;

class ItemVideoCollectionGetAction {

  /**
   * @var PlaylistRepository
   */
  private $playlistRepository;

  /**
   * @var VideoRepository
   */
  private $videoRepository;

  public function __construct(PlaylistRepository $playlistRepository, VideoRepository $videoRepository) {
    $this->playlistRepository = $playlistRepository;
    $this->videoRepository = $videoRepository;
  }

  public function __invoke(Request $request) {
    $id = (int) ($request->attributes['id'] ?? 0);
    if ($id <= 0) {
      throw new HttpException('Invalid request', 400);
    }

    $playlist = $this->playlistRepository->fetch($id);
    if (null === $playlist) {
      throw new HttpException('Not found', 404);
    }

    return [
      'data' => $this->videoRepository->fetchByPlaylistId($id)
    ];
  }
}
