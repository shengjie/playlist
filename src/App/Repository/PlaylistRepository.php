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

  public function addVideo(int $id, int $videoId, ?int $position): bool {
    $count = (int) $this->exec('SELECT count(*) FROM playlist_video WHERE playlist_id = ? AND video_id = ?', [
      1 => $id,
      2 => $videoId
    ])->fetchColumn();

    // video already exists
    if ($count > 0) {
      return false;
    }

    $maxPosition = (int) $this->exec('SELECT max(position) FROM playlist_video WHERE playlist_id = ?', [
      1 => $id
    ])->fetchColumn();

    $position = min($position, $maxPosition + 1);

    // shift position
    $this->exec('UPDATE playlist_video SET position = position + 1 WHERE playlist_id = ? AND position > ?', [
      1 => $id,
      2 => $position
    ]);

    // add video
    $this->exec('INSERT INTO playlist_video (playlist_id, video_id, created_at, position) VALUES (?, ?, NOW(), ?)', [
      1 => $id,
      2 => $videoId,
      3 => $position
    ]);

    return true;
  }

  public function removeVideo(int $id, int $videoId): bool {
    $position = (int) $this->exec('SELECT position FROM playlist_video WHERE playlist_id = ? AND video_id = ?', [
      1 => $id,
      2 => $videoId
    ])->fetchColumn();

    if ($position === 0) {
      // video not exists in playlist_video
      return false;
    }

    $this->exec('DELETE FROM playlist_video WHERE playlist_id = ? AND video_id = ?', [
      1 => $id,
      2 => $videoId
    ]);

    $this->exec('UPDATE playlist_video SET position = position - 1 WHERE playlist_id = ? AND position > ?',  [
      1 => $id,
      2 => $position
    ]);

    return true;
  }
}
