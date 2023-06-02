#!/bin/bash

DOCKER_BE = backend
OS := $(shell uname)
UID = 1000

help: ## Show this help message
	@echo 'usage: make [target]'
	@echo
	@echo 'targets:'
	@egrep '^(.+)\:\ ##\ (.+)' ${MAKEFILE_LIST} | column -t -c 2 -s ':#'

run: ## Start the containers
	docker network create prueba-network || true
	U_ID=${UID} docker compose up -d --remove-orphans

stop: ## Stop the containers
	U_ID=${UID} docker compose stop

restart: ## Restart the containers
	$(MAKE) stop && $(MAKE) run

ssh-be: ## ssh's into the be container
	docker exec -it ${DOCKER_BE} bash
