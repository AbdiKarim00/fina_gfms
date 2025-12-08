.PHONY: help setup up down restart logs shell tinker test migrate fresh reset clean rebuild backup lint fix db

# Colors for output
GREEN  := \033[0;32m
YELLOW := \033[1;33m
NC     := \033[0m

help: ## Show this help message
	@echo '$(GREEN)GFMS Development Commands$(NC)'
	@echo '=========================='
	@echo ''
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "  $(YELLOW)%-15s$(NC) %s\n", $$1, $$2}'
	@echo ''

setup: ## Complete setup (first time only)
	@./scripts/dev/setup.sh

up: ## Start all services
	@echo "$(GREEN)Starting services...$(NC)"
	@docker-compose up -d
	@echo "$(GREEN)✓ Services started$(NC)"

down: ## Stop all services
	@echo "$(YELLOW)Stopping services...$(NC)"
	@docker-compose down
	@echo "$(GREEN)✓ Services stopped$(NC)"

restart: down up ## Restart all services

logs: ## View logs (use 'make logs service=app' for specific service)
	@docker-compose logs -f $(service)

shell: ## Access Laravel container shell
	@docker-compose exec app bash

tinker: ## Open Laravel Tinker
	@docker-compose exec app php artisan tinker

test: ## Run tests
	@./scripts/dev/test.sh

migrate: ## Run database migrations
	@echo "$(GREEN)Running migrations...$(NC)"
	@docker-compose exec app php artisan migrate
	@echo "$(GREEN)✓ Migrations complete$(NC)"

fresh: ## Fresh database with seeds
	@echo "$(YELLOW)Refreshing database...$(NC)"
	@docker-compose exec app php artisan migrate:fresh --seed
	@echo "$(GREEN)✓ Database refreshed$(NC)"

reset: ## Reset database (interactive)
	@./scripts/dev/reset.sh

clean: ## Clear all caches
	@echo "$(GREEN)Clearing caches...$(NC)"
	@docker-compose exec app php artisan config:clear
	@docker-compose exec app php artisan cache:clear
	@docker-compose exec app php artisan route:clear
	@docker-compose exec app php artisan view:clear
	@echo "$(GREEN)✓ Caches cleared$(NC)"

rebuild: ## Rebuild Docker containers
	@echo "$(YELLOW)Rebuilding containers...$(NC)"
	@docker-compose down
	@docker-compose build --no-cache
	@docker-compose up -d
	@echo "$(GREEN)✓ Containers rebuilt$(NC)"

backup: ## Backup database
	@echo "$(GREEN)Backing up database...$(NC)"
	@docker-compose exec postgres pg_dump -U gfms gfms > backup_$$(date +%Y%m%d_%H%M%S).sql
	@echo "$(GREEN)✓ Backup complete$(NC)"

lint: ## Check code style
	@echo "$(GREEN)Checking code style...$(NC)"
	@docker-compose exec app ./vendor/bin/pint --test
	@echo "$(GREEN)✓ Code style check complete$(NC)"

fix: ## Fix code style
	@echo "$(GREEN)Fixing code style...$(NC)"
	@docker-compose exec app ./vendor/bin/pint
	@echo "$(GREEN)✓ Code style fixed$(NC)"

db: ## Access database CLI
	@docker-compose exec postgres psql -U gfms -d gfms

queue: ## Start queue worker
	@docker-compose exec app php artisan queue:work

horizon: ## Start Horizon
	@docker-compose exec app php artisan horizon

status: ## Show service status
	@docker-compose ps

install: ## Install backend dependencies
	@docker-compose exec app composer install

update: ## Update backend dependencies
	@docker-compose exec app composer update

seed: ## Seed database
	@docker-compose exec app php artisan db:seed

routes: ## List all routes
	@docker-compose exec app php artisan route:list

models: ## List all models
	@docker-compose exec app php artisan model:show

optimize: ## Optimize Laravel
	@docker-compose exec app php artisan optimize

key: ## Generate application key
	@docker-compose exec app php artisan key:generate
