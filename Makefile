lint: ## Vérifie le code sans modifier
	@echo "Vérification du style PHP..."
	vendor/bin/php-cs-fixer fix --dry-run --diff
	@echo "Analyse statique PHP..."
	vendor/bin/phpstan analyse src --memory-limit=1G
	@echo "Vérification du style JS..."
	npm run lint

fix: ## Corrige le code automatiquement
	@echo "Nettoyage du code PHP..."
	vendor/bin/php-cs-fixer fix
	@echo "Formatage JS..."
	npm run format