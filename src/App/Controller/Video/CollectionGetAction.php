<?php

namespace App\Controller\Video;

use Component\Http\JsonResponse;
use Component\Http\Request;
use App\Repository\VideoRepository;

class CollectionGetAction {

  /**
   * @var VideoRepository
   */
  private $videoRepository;

  public function __construct(VideoRepository $videoRepository) {
    $this->videoRepository = $videoRepository;
  }

  public function __invoke(Request $request) {
    $videos = $this->videoRepository->fetchAll();
    return [
      'data' => $videos
    ];
  }
}
