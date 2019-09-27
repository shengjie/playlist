<?php

namespace App\Repository;

use Component\Persistence\AbstractRepository;
use App\Dto\Playlist;

class PlaylistRepository extends AbstractRepository {

  public function fetch(int $id): ?Playlist {
    $stmt = $this->prepare('SELECT * FROM playlist WHERE id = ?');
    $stmt->bindValue(1, $id);
    $stmt->execute();
    $stmt->setFetchMode(\PDO::FETCH_ASSOC);
    $row = $stmt->fetch();
    if (false === $row) {
      return null;
    }

    return Playlist::fromDbResult($row);
  }

  public function fetchAll() {
    $stmt = $this->prepare('SELECT * FROM playlist');
    $stmt->execute();
    $stmt->setFetchMode(\PDO::FETCH_ASSOC);

    $videos = [];
    foreach($stmt as $row) {
      $video = Playlist::fromDbResult($row);
      $videos[] = $video;
    }
    return $videos;
  }

  public function create(array $fields): Playlist {
    $stmt = $this->prepare('INSERT INTO playlist (name, created_at) VALUES (?, NOW())');
    $stmt->bindValue(1, $fields['name']);
    $stmt->execute();

    return $this->fetch((int) $this->getConnection()->lastInsertId());
  }

  public function update(int $id, array $fields): Playlist {
    $stmt = $this->prepare('UPDATE playlist SET name = ?, updated_at=NOW() WHERE id = ?');
    $stmt->bindValue(1, $fields['name']);
    $stmt->bindValue(2, $id);
    $stmt->execute();

    return $this->fetch($id);
  }

  public function remove(int $id) {
    $this->prepare('DELETE FROM playlist WHERE id = ?');
    $stmt->bindValue(1, $id);
    $stmt->execute();
  }

  public function addVideo(int $id, int $videoId, ?int $position): void {
    $stmt = $this->prepare('INSERT INTO playlist_video (playlist_id, video_id, created_at, position)
SELECT ?, ?, NOW(), ?');
    $stmt->bindValue(1, $id);
    $stmt->bindValue(2, $videoId);
    $stmt->bindValue(3, $position ?? PHP_INT_MAX);
    $stmt->execute();

    $this->rebuildPosition($id);
  }

  public function removeVideo(int $id, int $videoId): void {
    $stmt = $this->prepare('DELETE FROM playlist_video WHERE playlist_id = ?, video_id = ?');
    $stmt->bindValue(1, $id);
    $stmt->bindValue(2, $videoId);
    $stmt->execute();

    $this->rebuildPosition($id);
  }

  public function rebuildPosition(int $id) {
    $stmt = $this->prepare('UPDATE playlist_video pv2
INNER JOIN (
 SELECT
 _pv.playlist_id,
 _pv.video_id,
 ROW_NUMBER() OVER (PARTITION BY _pv.playlist_id ORDER BY _pv.position ASC, _pv.created_at DESC) AS position
 FROM playlist_video _pv
 WHERE _pv.playlist_id = ?
) pv ON pv.playlist_id = pv2.playlist_id AND pv2.video_id = pv.video_id
SET pv2.position = pv.position');
    $stmt->bindValue(1, $id);
    $stmt->execute();
  }
}
