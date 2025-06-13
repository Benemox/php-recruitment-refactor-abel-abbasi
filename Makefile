up:
	docker compose up -d --build

down:
	docker compose down --volumes --remove-orphans

composer:
	docker compose exec php composer $(cmd)

test:
	docker compose exec php vendor/bin/phpunit

logs:
	docker-compose logs -f
