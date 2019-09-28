<?php

namespace App\Controller\Playlist;

use Component\Http\JsonResponse;
use Component\Http\Request;
use Component\Http\Response;
use Component\Http\Exceptions\HttpException;
use App\Repository\PlaylistRepository;

class ItemDeleteAction {
  /**
   * @var PlaylistRepository
   */
  private $playlistRepository;

  public function __construct(PlaylistRepository $playlistRepository) {
    $this->playlistRepository = $playlistRepository;
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

    $this->playlistRepository->remove($id);

    return [
      '_statusCode' => 204
    ];
  }
}
