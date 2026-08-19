.PHONY: up down test reset

up:
	docker compose up --build

down:
	docker compose down

test:
	docker compose exec app php artisan test --compact

reset:
	docker compose down -v
	docker compose up --build
