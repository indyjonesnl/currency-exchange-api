.PHONY: help build rebuild up down start stop destroy restart enter ps env cache

ifndef ENV
override ENV = dev
endif

PROJECT = exchange-api
COMPOSE_CMD = docker compose -p $(PROJECT) -f docker-compose.yaml
BUILD_CMD = $(COMPOSE_CMD) build --parallel --pull

help: ## Show the available commands.
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)

build: ## Build docker images.
	$(BUILD_CMD)

rebuild: ## Force rebuild of docker images from scratch.
	$(BUILD_CMD) --no-cache

up: ## Build and start docker containers.
	$(COMPOSE_CMD) up -d

start: ## Start docker containers.
	$(COMPOSE_CMD) start

down: ## Remove docker containers.
	$(COMPOSE_CMD) down

stop: ## Stop docker containers.
	$(COMPOSE_CMD) stop

destroy: ## Delete docker images.
	$(COMPOSE_CMD) down -v

ps: ## Show running docker containers for this project.
	$(COMPOSE_CMD) ps

restart: ## Restart all docker containers. Or use make restart CONTAINER=php to restart a specific container.
	$(COMPOSE_CMD) restart $(CONTAINER)

enter: ## Enter the PHP container.
	$(COMPOSE_CMD) exec -it php ash

env: ## Composer dump env variables. Use "make env ENV=prod" for prod env.
	composer dump-env $(ENV)

cache: ## Clear (and warmup) the application cache.
	$(COMPOSE_CMD) exec php sh -c "bin/console cache:clear"
