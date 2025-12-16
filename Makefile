.PHONY: help analyse format format-check quality test quality-all rector install

help: ## Affiche cette aide
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-20s\033[0m %s\n", $$1, $$2}'

install: ## Installe les dépendances Composer
	composer install

analyse: ## Lance PHPStan pour l'analyse statique (niveau 8)
	@# Note: S'assure que phpstan.neon existe avant de lancer PHPStan
	@if [ ! -f phpstan.neon ]; then cp phpstan.neon.txt phpstan.neon; fi
	@vendor/bin/phpstan analyse --level=8 --memory-limit=512M app

format: ## Formate le code avec Laravel Pint
	vendor/bin/pint

format-check: ## Vérifie le formatage sans modifier les fichiers
	vendor/bin/pint --test

quality: ## Lance tous les outils de qualité (analyse + format check)
	@echo "🔍 Analyse statique avec PHPStan..."
	@vendor/bin/phpstan analyse --level=8 --memory-limit=512M app
	@echo "✨ Vérification du formatage avec Pint..."
	@vendor/bin/pint --test

test: ## Lance les tests unitaires (exclut Browser)
	php artisan test --exclude-testsuite=Browser

test-without-browser: ## Lance les tests sans Browser (Unit uniquement)
	php artisan test --exclude-testsuite=Browser

test-unit: ## Lance uniquement les tests unitaires (isolation avec mocks, Query tests utilisent SQLite en mémoire)
	php artisan test --testsuite=Unit


test-all: ## Lance tous les tests (Unit + Browser)
	php artisan test

test-parallel: ## Lance les tests en parallèle (Unit uniquement, exclut Browser)
	@# ⚠️ ATTENTION: ParaTest ne fonctionne pas correctement sur Windows natif
	@# Sur Windows, utilisez plutôt: make test (sans parallélisme)
	@if [ "$(OS)" = "Windows_NT" ]; then \
		echo "⚠️  ParaTest ne fonctionne pas sur Windows. Exécution séquentielle..."; \
		php artisan test --exclude-testsuite=Browser; \
	elif command -v vendor/bin/paratest >/dev/null 2>&1 || php -r "require 'vendor/autoload.php'; class_exists('ParaTest\Console\Commands\ParaTestCommand');" 2>/dev/null; then \
		php artisan test --parallel --testsuite=Unit; \
	else \
		echo "⚠️  ParaTest n'est pas installé. Installation..."; \
		composer require --dev brianium/paratest --quiet && php artisan test --parallel --testsuite=Unit; \
	fi

quality-all: ## Lance analyse + format + tests
	@echo "🔍 Analyse statique avec PHPStan..."
	@if [ ! -f phpstan.neon ]; then cp phpstan.neon.txt phpstan.neon; fi
	@vendor/bin/phpstan analyse --memory-limit=512M
	@echo "✨ Vérification du formatage avec Pint..."
	@vendor/bin/pint --test
	@echo "🧪 Lancement des tests..."
	@php artisan test


clean: ## Nettoyer tous les caches (ne supprime PAS routes/web.php ni phpstan.neon, seulement le cache)
	php artisan config:clear
	php artisan cache:clear
	php artisan route:clear  # Supprime seulement le cache des routes, PAS routes/web.php
	php artisan view:clear
	php artisan optimize:clear
	php artisan optimize
	# Note: phpstan.neon est préservé car c'est un fichier de configuration, pas un cache
	
rector: ## Lance Rector en mode dry-run pour voir les suggestions
	vendor/bin/rector process --dry-run
	@# Note: Rector ne doit pas supprimer phpstan.neon (fichier de configuration)

rector-apply: ## Applique les modifications suggérées par Rector
	vendor/bin/rector process
	@# Note: Rector ne doit pas supprimer phpstan.neon (fichier de configuration)

swagger-generate: ## Génère la documentation Swagger/OpenAPI
	php artisan l5-swagger:generate

# Note: CI/CD est configuré avec :
# - GitHub Actions : .github/workflows/ci.yml (s'exécute automatiquement sur push/PR)
# - GitLab CI/CD : .gitlab-ci.yml (s'exécute automatiquement sur merge requests)
# Les commandes ci-dessus peuvent être exécutées localement ou dans les pipelines CI/CD
