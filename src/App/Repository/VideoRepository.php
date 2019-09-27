<?php

namespace App\Repository;

use Component\Persistence\AbstractRepository;
use App\Dto\Video;

class VideoRepository extends AbstractRepository {

  public function fetch(int $id): ?Video {
    $stmt = $this->prepare('SELECT * FROM video WHERE id = ?');
    $stmt->bindValue(1, $id);
    $stmt->execute();
    $stmt->setFetchMode(\PDO::FETCH_ASSOC);
    $row = $stmt->fetch();
    if (false === $row) {
      return null;
    }

    return Video::fromDbResult($row);
  }

  public function fetchAll() {
    $conn = $this->getConnection();
    $stmt = $conn->prepare('SELECT * FROM video');
    $stmt->execute();
    $stmt->setFetchMode(\PDO::FETCH_ASSOC);

    $videos = [];
    foreach($stmt as $row) {
      $video = Video::fromDbResult($row);
      $videos[] = $video;
    }
    return $videos;
  }

  public function fetchByPlaylistId(int $id) {
    $stmt = $this->prepare('SELECT v.* FROM playlist_video pv INNER JOIN video v ON pv.video_id = v.id WHERE pv.playlist_id = ? ORDER BY pv.position ASC');
    $stmt->bindValue(1, $id);
    $stmt->execute();

    $stmt->setFetchMode(\PDO::FETCH_ASSOC);
    $videos = [];
    foreach($stmt as $row) {
      $video = Video::fromDbResult($row);
      $videos[] = $video;
    }
    return $videos;
  }
}
