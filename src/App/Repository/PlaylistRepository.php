<?php

namespace App\Repository;

use Component\Persistence\AbstractRepository;
use App\Dto\Playlist;

class PlaylistRepository extends AbstractRepository {

  public function fetch(int $id): ?Playlist {
    $stmt = $this->exec('SELECT * FROM playlist WHERE id = ?', [1 => $id]);
    $stmt->setFetchMode(\PDO::FETCH_ASSOC);
    $row = $stmt->fetch();
    if (false === $row) {
      return null;
    }

    return Playlist::fromDbResult($row);
  }

  public function fetchAll() {
    $stmt = $this->exec('SELECT * FROM playlist');
    $stmt->setFetchMode(\PDO::FETCH_ASSOC);

    $videos = [];
    foreach($stmt as $row) {
      $video = Playlist::fromDbResult($row);
      $videos[] = $video;
    }
    return $videos;
  }

  public function create(array $fields): Playlist {
    $stmt = $this->exec('INSERT INTO playlist (name, created_at) VALUES (?, NOW())', [
      1 => $fields['name']
    ]);

    return $this->fetch((int) $this->getConnection()->lastInsertId());
  }

  public function update(int $id, array $fields): Playlist {
    $stmt = $this->exec('UPDATE playlist SET name = ?, updated_at=NOW() WHERE id = ?', [
      1 => $fields['name'],
      2 => $id
    ]);
    return $this->fetch($id);
  }

  public function remove(int $id): bool {
    $stmt = $this->exec('DELETE FROM playlist WHERE id = ?', [1 => $id]);
    return $stmt->rowCount() > 0;
  }

  public function addVideo(int $id, int $videoId, ?int $position): void {
    $this->exec('INSERT INTO playlist_video (playlist_id, video_id, created_at, position)
SELECT ?, ?, NOW(), ?', [
  1 => $id,
  2 => $videoId,
  3 => $position ?? PHP_INT_MAX
]);
    $this->rebuildPosition($id);
  }

  public function removeVideo(int $id, int $videoId): bool {
    $stmt = $this->exec('DELETE FROM playlist_video WHERE playlist_id = ? AND video_id = ?', [
      1 => $id,
      2 => $videoId
    ]);
    $success = $stmt->rowCount() > 0;
    if ($success) {
      $this->rebuildPosition($id);
    }
    return $success;
  }

  public function rebuildPosition(int $id) {
    $stmt = $this->exec('UPDATE playlist_video pv2
INNER JOIN (
 SELECT
 _pv.playlist_id,
 _pv.video_id,
 ROW_NUMBER() OVER (PARTITION BY _pv.playlist_id ORDER BY _pv.position ASC, _pv.created_at DESC) AS position
 FROM playlist_video _pv
 WHERE _pv.playlist_id = ?
) pv ON pv.playlist_id = pv2.playlist_id AND pv2.video_id = pv.video_id
SET pv2.position = pv.position', [1 => $id]);
  }
}
