<?php

namespace App\Controller\Playlist;

use Component\Http\JsonResponse;
use Component\Http\Request;
use Component\Http\Response;
use Component\Http\Exceptions\HttpException;
use App\Repository\PlaylistRepository;
use App\Hateoas\PlaylistHateoasResolver;
use App\Dto\Playlist;


class CollectionPostAction {
  /**
   * @var PlaylistRepository
   */
  private $playlistRepository;

  public function __construct(PlaylistRepository $playlistRepository) {
    $this->playlistRepository = $playlistRepository;
  }

  public function __invoke(Request $request) {

    if (!empty(\array_diff(array_keys($request->post), ['name']))) {
      throw new HttpException('invalid post data: only "name" is allowed', 400);
    }

    if (empty($request->post['name']) || strlen($request->post['name']) > 255) {
      throw new HttpException('invalid post data: field "name" length must be between 1 and 255 characters', 400);
    }

    $playlist = $this->playlistRepository->create([
      'name' => $request->post['name']
    ]);

    return [
      'data' => $playlist,
      '_statusCode' => 201
    ];
  }
}
