CREATE TABLE video (
   id INT UNSIGNED NOT NULL AUTO_INCREMENT,
   title VARCHAR(255) NOT NULL,
   thumbnail VARCHAR(255) NOT NULL,
   video_url VARCHAR(255) NOT NULL,
   created_at DATETIME NOT NULL,
   updated_at DATETIME DEFAULT NULL,
   PRIMARY KEY(id)
);

CREATE TABLE playlist (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL,
  created_at DATETIME NOT NULL,
  updated_at DATETIME DEFAULT NULL,
  PRIMARY KEY(id)
);

CREATE TABLE playlist_video (
  playlist_id INT UNSIGNED NOT NULL,
  video_id INT UNSIGNED NOT NULL,
  position INT UNSIGNED,
  created_at DATETIME NOT NULL,
  PRIMARY KEY (playlist_id, video_id)
);

ALTER TABLE playlist_video ADD FOREIGN KEY (playlist_id) REFERENCES playlist(id) ON DELETE CASCADE;
ALTER TABLE playlist_video ADD FOREIGN KEY (video_id) REFERENCES video(id);


-- demo data
INSERT INTO video (id, title, thumbnail, video_url, created_at)
VALUES
  (1, 'Video 1', 'http://example.com/video1.jpg', 'http://example.com/video1.mp4', NOW()),
  (2, 'Video 2', 'http://example.com/video2.jpg', 'http://example.com/video2.mp4', NOW()),
  (3, 'Video 3', 'http://example.com/video3.jpg', 'http://example.com/video3.mp4', NOW()),
  (4, 'Video 4', 'http://example.com/video4.jpg', 'http://example.com/video4.mp4', NOW()),
  (5, 'Video 5', 'http://example.com/video5.jpg', 'http://example.com/video5.mp4', NOW())
;

INSERT INTO playlist (id, name, created_at)
VALUES
  (1, 'Playlist 1', NOW()),
  (2, 'Playlist 2', NOW())
;

INSERT INTO playlist_video (playlist_id, video_id, position, created_at)
VALUES
  (1, 1, 1, NOW()),
  (1, 3, 2, NOW()),
  (1, 5, 3, NOW()),
  (2, 2, 1, NOW()),
  (2, 4, 2, NOW())
;
