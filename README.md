# Playlist demo
This is a simple playlist REST api demo based on php/mysql

# Usage

* Notice: this demo requires `docker` and `docker-compose`.

* Execute following command to start containers
```
make build && make run
```
* After all containers are started, You can then navigate to http://php-docker.local:8080/test.html to testing api behavoir in browser.
* To manage database, please visit http://localhost:8000/ (username: root, password: rootpassword)

# Endpoints


| Method | URL | Post | Description |
|---|---|---|
| GET | /videos  | - | Get all videos |
| GET | /videos/{id} | - | Get detail of one video |
| GET | /playlists  | - | Get all playlists |
| GET | /playlists/{id}  | - | Get detail of one playlist |
| GET | /playlists/{id}/videos  | - | Get all videos of one playlist |
| POST | /playlists/{id}/videos  | video_id, position | Add a video to playlist |
| DELETE | /playlists/{id}/videos/{vid} | - | Delete video from playlist |
| PUT | /playlists/{id}  | name | Update playlist information |
| POST | /playlists  | name | Create a new playist |
| DELETE | /playlists/{id} | - | Delete one playist |
