#!/bin/bash

# Script pour vider le cache Symfony en production
# Usage: ./CLEAR_CACHE_PRODUCTION.sh

echo "=========================================="
echo "Vidage du cache Symfony en production"
echo "=========================================="

# Aller dans le répertoire du projet
cd /var/www/blog-api || exit 1

echo ""
echo "1. Vérification du répertoire..."
pwd

echo ""
echo "2. Suppression du cache de production..."
rm -rf var/cache/prod/*

echo ""
echo "3. Vérification que le cache est vide..."
if [ -z "$(ls -A var/cache/prod/)" ]; then
    echo "✅ Cache vidé avec succès"
else
    echo "⚠️  Le cache n'est pas complètement vide"
    ls -la var/cache/prod/
fi

echo ""
echo "4. Vérification des permissions..."
chmod -R 775 var/cache
chown -R www-data:www-data var/cache

echo ""
echo "5. Régénération du cache..."
php bin/console cache:warmup --env=prod --no-debug

if [ $? -eq 0 ]; then
    echo "✅ Cache régénéré avec succès"
else
    echo "❌ Erreur lors de la régénération du cache"
    echo "Tentative avec le chemin complet de PHP..."
    /usr/bin/php8.2-cli bin/console cache:warmup --env=prod --no-debug
fi

echo ""
echo "6. Vérification finale..."
if [ -f "var/cache/prod/App_KernelProdContainer.php" ]; then
    echo "✅ Cache régénéré correctement"
    echo "Le conteneur de services a été recréé"
else
    echo "⚠️  Le fichier de conteneur n'a pas été créé"
fi

echo ""
echo "=========================================="
echo "Terminé !"
echo "=========================================="
echo ""
echo "Testez maintenant l'API :"
echo "curl https://celestinbosongoservices.com/api/blog_view_public?lang=fr"
echo ""

