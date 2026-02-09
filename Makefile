lint: ## Check the code without modifying it
	@echo "Checking PHP style..."
	vendor/bin/php-cs-fixer fix --dry-run --diff
	@echo "PHP static analysis..."
	vendor/bin/phpstan analyse src --memory-limit=1G
	@echo "Checking JS style..."
	npm run lint

fix: ## Fix the code automatically
	@echo "Cleaning up PHP code..."
	vendor/bin/php-cs-fixer fix
	@echo "Formatting JS..."
	npm run format