
build:
	docker run --rm --volume $PWD:/app composer install
	docker-compose build
	
run:
	docker-compose up

clean:
	docker-compose down
