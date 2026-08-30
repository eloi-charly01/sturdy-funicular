PHP_CONTAINER=task_manager_php

up:
	docker-compose up -d --wait

down:
	docker-compose down -v

logs:
	docker-compose logs -f

bash:
	docker exec -it $(PHP_CONTAINER) bash

composer-install:
	docker exec -it $(PHP_CONTAINER) composer install

migrate:
	docker exec -it $(PHP_CONTAINER) php bin/console doctrine:migrations:migrate --no-interaction

create-db:
	docker exec -it $(PHP_CONTAINER) php bin/console doctrine:database:create --if-not-exists

update-db:
	docker exec -it $(PHP_CONTAINER) php bin/console doctrine:schema:update --force

fixtures:
	docker exec -it $(PHP_CONTAINER) php bin/console doctrine:fixtures:load --no-interaction

cache-clear:
	docker exec -it $(PHP_CONTAINER) php bin/console cache:clear

test:
	docker exec -it $(PHP_CONTAINER) php bin/phpunit

audit:
	docker exec -it $(PHP_CONTAINER) composer audit

permissions:
	sudo chmod -R 777 var

symfony-serve:
	symfony serve

restart:
	docker-compose down -v && docker-compose up -d --build

node-install:
	docker exec -it task_manager_node npm install

node-watch:
	docker exec -it task_manager_node npm run watch