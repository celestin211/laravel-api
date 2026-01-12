# 🔧 Commandes pour vider le cache en production

## ⚠️ IMPORTANT : Exécutez ces commandes directement sur le serveur

```bash
# 1. Aller dans le répertoire du projet
cd /var/www/blog-api

# 2. SUPPRIMER COMPLÈTEMENT LE CACHE (OBLIGATOIRE)
rm -rf var/cache/prod/*

# 3. Vérifier que le cache est bien supprimé
ls -la var/cache/prod/
# Le répertoire devrait être vide ou presque vide

# 4. Régénérer le cache avec le chemin complet de PHP
/usr/bin/php8.2-cli bin/console cache:warmup --env=prod --no-debug

# 5. Si la commande ci-dessus ne fonctionne pas, essayer :
php bin/console cache:warmup --env=prod --no-debug

# 6. Vérifier les permissions
chmod -R 775 var/cache
chown -R www-data:www-data var/cache

# 7. Tester immédiatement
curl https://celestinbosongoservices.com/api/blog_view_public?lang=fr
```

## ✅ Vérification après vidage du cache

```bash
# Vérifier que le conteneur a été régénéré
ls -la var/cache/prod/Container*/

# Vous devriez voir un nouveau fichier Container avec un nom différent
# (pas Container2etNqWD)
```

## 🔍 Si ça ne fonctionne toujours pas

### Vérifier que les fichiers modifiés sont bien sur le serveur :

```bash
# Vérifier UpdateBlogHandler
grep -A 5 "__construct" src/Handler/Blog/UpdateBlogHandler.php

# Vous devriez voir les 3 dépendances :
# - BlogManager
# - CategoryRepository  
# - UserRepository
```

### Si les fichiers ne sont pas à jour :

```bash
# Vous devez d'abord déployer les fichiers modifiés
# Puis vider le cache
```

## 📝 Commande rapide (tout en une ligne)

```bash
cd /var/www/blog-api && rm -rf var/cache/prod/* && /usr/bin/php8.2-cli bin/console cache:warmup --env=prod --no-debug && chmod -R 775 var/cache && chown -R www-data:www-data var/cache
```

