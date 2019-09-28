
build:
	docker run --rm --volume $(CURDIR):/usr/src -w /usr/src composer install
	docker-compose build

run: build
	docker-compose up

clean:
	docker-compose down
