<?php

namespace App\Controller\Playlist;

use Component\Http\JsonResponse;
use Component\Http\Request;
use Component\Http\Exceptions\HttpException;
use App\Repository\VideoRepository;
use App\Repository\PlaylistRepository;

class ItemVideoItemDeleteAction {

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
      throw new HttpException('Playlist not found', 404);
    }

    $videoId = (int) $request->attributes['vid'];
    if ($videoId <= 0) {
      throw new HttpException('invalid request', 400);
    }

    $video = $this->videoRepository->fetch($videoId);
    if (null === $video) {
      throw new HttpException('Video not found', 404);
    }

    if (!$this->playlistRepository->removeVideo($id, $videoId)) {
      throw new HttpException('Video not exists in playlist', 400);
    }

    return [
      '_statusCode' => 204
    ];
  }
}
