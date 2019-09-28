<?php

namespace App\Dto;

class Playlist {
  /**
   * @var int
   */
  public $id;

  /**
   * @var string
   */
  public $name;

  /**
   * @var string
   */
  public $created_at;

  /**
   * @var string
   */
  public $updated_at;

  public static function fromDbResult(array $dbResult): self {
    $playlist = new self();
    $playlist->id = (int) $dbResult['id'];
    $playlist->name = $dbResult['name'];
    $playlist->created_at = $dbResult['created_at'];
    $playlist->updated_at = $dbResult['created_at'];

    return $playlist;
  }
}
