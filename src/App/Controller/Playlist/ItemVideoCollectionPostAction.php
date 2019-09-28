<?php

namespace App\Controller\Playlist;

use Component\Http\JsonResponse;
use Component\Http\Request;
use Component\Http\Exceptions\HttpException;
use App\Repository\VideoRepository;
use App\Repository\PlaylistRepository;

class ItemVideoCollectionPostAction {

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

    if (!empty(\array_diff(array_keys($request->post), ['video_id', 'position']))) {
      throw new HttpException('invalid post data: only "video_id", "position" is allowed', 400);
    }

    $videoId = (int) $request->post['video_id'];
    if ($videoId <= 0) {
      throw new HttpException('invalid post data: video_id', 400);
    }

    $position = null;
    if (\array_key_exists('position', $request->post)) {
      $position = (int) $request->post['position'];
      if ($position <= 0) {
        throw new HttpException('invalid post data: position', 400);
      }
    }

    $video = $this->videoRepository->fetch($videoId);
    if (null === $video) {
      throw new HttpException('invalid post data: video_id', 400);
    }

    try {
      $this->playlistRepository->addVideo($id, $videoId, $position);
    } catch(\PDOException $e) {
      if ('23000' === $e->getCode()) {
        // duplicated primary key
        throw new HttpException('Video already present in target playlist', 400);
      } else {
        throw $e;
      }
    }

    return [
      '_statusCode' => 204
    ];
  }
}
