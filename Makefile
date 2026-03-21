.PHONY: help init start stop restart db migration fixtures db-reset cc

help: ## Zeigt diese Hilfe an
	@grep -E '^[a-zA-Z0-9_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

init: ## Projekt initialisieren (Dependencies, DB & Fixtures)
	@echo "Installiere PHP Dependencies..."
	composer install
	@echo "Installiere JS Dependencies..."
	npm install
	@echo "Richte Datenbank ein..."
	php bin/console doctrine:database:create --if-not-exists
	php bin/console doctrine:migrations:migrate --no-interaction
	@echo "Lade Testdaten (Fixtures)..."
	php bin/console doctrine:fixtures:load --no-interaction
	@echo "Setup fertig! Starte den Server mit 'make start'"

start: ## Startet Symfony Server und Tailwind-Watcher
	symfony server:start -d
	php bin/console tailwind:build --watch

stop: ## Stoppt Symfony Server
	symfony server:stop

restart: stop start ## Startet alles neu

db: ## Fuehrt Datenbank-Migrationen aus
	php bin/console doctrine:migrations:migrate --no-interaction

migration: ## Erstellt eine neue Migration basierend auf Entity-Aenderungen
	php bin/console make:migration

fixtures: ## Laedt die Testdaten neu
	php bin/console doctrine:fixtures:load --no-interaction

db-reset: ## Loescht DB, migriert neu & laedt Fixtures
	-php bin/console doctrine:database:drop --force --if-exists
	php bin/console doctrine:database:create
	php bin/console doctrine:migrations:migrate --no-interaction
	php bin/console doctrine:fixtures:load --no-interaction

cc: ## Cache leeren
	php bin/console cache:clear
