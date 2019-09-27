<?php

namespace App\Dto;

class Video {
  /**
   * @var int
   */
  public $id;

  /**
   * @var string
   */
  public $title;

  /**
   * @var string
   */
  public $thumbnail;

  /**
   * @var string
   */
  public $video_url;

  /**
   * @var string
   */
  public $created_at;

  /**
   * @var string
   */
  public $updated_at;

  public static function fromDbResult(array $dbResult): self {
    $video = new self();
    $video->id = (int) $dbResult['id'];
    $video->title = $dbResult['title'];
    $video->thumbnail = $dbResult['thumbnail'];
    $video->video_url = $dbResult['video_url'];
    $video->created_at = $dbResult['created_at'];
    $video->updated_at = $dbResult['created_at'];

    return $video;
  }
}
