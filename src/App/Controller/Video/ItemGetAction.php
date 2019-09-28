<?php

namespace App\Controller\Video;

use Component\Http\JsonResponse;
use Component\Http\Request;
use Component\Http\Response;
use Component\Http\Exceptions\HttpException;
use App\Repository\VideoRepository;

class ItemGetAction {
  /**
   * @var VideoRepository
   */
  private $videoRepository;

  public function __construct(VideoRepository $videoRepository) {
    $this->videoRepository = $videoRepository;
  }

  public function __invoke(Request $request) {
    $id = (int) ($request->attributes['id'] ?? 0);
    if ($id <= 0) {
      throw new HttpException('Invalid request', 400);
    }

    $video = $this->videoRepository->fetch($id);
    if (null === $video) {
      throw new HttpException('Not found', 404);
    }

    return [
      'data' => $video
    ];
  }
}
