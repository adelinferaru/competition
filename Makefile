#!/usr/bin/make

SHELL = /bin/sh

UID := $(shell id -u)
GID := $(shell id -g)

export UID
export GID

laravel_app := docker exec -it awesome-quotes-laravel.test-1
pest := ./vendor/bin/pest

.PHONY: dev
dev:
	@docker-compose down && \
		docker-compose build --pull --no-cache && \
		docker-compose \
			-f docker-compose.yml \
			up -d --remove-orphans && \
	$(laravel_app) composer install && \
    $(laravel_app) php artisan migrate

.PHONY: test
test:
	$(laravel_app) $(pest)

