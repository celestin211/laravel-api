# Correction : Erreur UpdateBlogHandler - Cache à vider

## Problème
```
Too few arguments to function App\Handler\Blog\UpdateBlogHandler::__construct(), 
1 passed in /var/www/blog-api/var/cache/prod/Container2etNqWD/getBlogControllerService.php 
on line 23 and exactly 3 expected
```

## Cause
Le cache de production n'a pas été vidé après l'ajout de nouvelles dépendances dans `UpdateBlogHandler`. 
Le conteneur de services Symfony utilise encore l'ancienne configuration avec seulement `BlogManager`.

## Solution

### 1. Se connecter au serveur
```bash
ssh votre-utilisateur@celestinbosongoservices.com
```

### 2. Aller dans le répertoire du projet
```bash
cd /var/www/blog-api
```

### 3. Vider le cache de production
```bash
# IMPORTANT : Utiliser le chemin complet de PHP si nécessaire
# Option 1 : Supprimer manuellement le répertoire cache (RECOMMANDÉ)
rm -rf var/cache/prod/*

# Option 2 : Vider avec la commande Symfony
/usr/bin/php8.2-cli bin/console cache:clear --env=prod --no-debug

# OU si php est dans le PATH
php bin/console cache:clear --env=prod --no-debug
```

### 4. Vérifier les permissions
```bash
# S'assurer que les permissions sont correctes
chmod -R 775 var/cache
chown -R www-data:www-data var/cache
```

### 5. Régénérer le cache (optionnel mais recommandé)
```bash
# Utiliser le chemin complet de PHP
/usr/bin/php8.2-cli bin/console cache:warmup --env=prod --no-debug

# OU si php est dans le PATH
php bin/console cache:warmup --env=prod --no-debug
```

### 5bis. Vérifier que les fichiers modifiés sont bien déployés
```bash
# Vérifier que UpdateBlogHandler a bien les 3 dépendances
grep -A 5 "__construct" src/Handler/Blog/UpdateBlogHandler.php

# Vous devriez voir :
# private readonly BlogManager $blogManager,
# private readonly CategoryRepository $categoryRepository,
# private readonly UserRepository $userRepository,
```

### 6. Vérifier que le cache est bien vidé
```bash
ls -la var/cache/prod/
# Le répertoire devrait être vide ou contenir seulement les nouveaux fichiers
```

## Vérification

### 1. Vérifier que le cache est bien vidé
```bash
# Le répertoire devrait être vide ou contenir seulement les nouveaux fichiers
ls -la var/cache/prod/

# Vérifier que le conteneur a été régénéré
ls -la var/cache/prod/Container*/
```

### 2. Tester l'endpoint
```bash
curl https://celestinbosongoservices.com/api/blog_view_public?lang=fr
```

L'erreur devrait disparaître et l'API devrait fonctionner correctement.

### 3. Si l'erreur persiste

Vérifiez que les fichiers modifiés sont bien sur le serveur :
```bash
# Vérifier UpdateBlogHandler
cat src/Handler/Blog/UpdateBlogHandler.php | grep -A 3 "__construct"

# Vérifier BlogController
cat src/Controller/BlogController.php | grep -A 2 "UpdateBlogCommand"

# Si les fichiers ne sont pas à jour, vous devez les déployer
```

## Note importante

Après chaque modification de :
- Constructeurs de services
- Dépendances injectées
- Configuration des services

Il est **essentiel** de vider le cache en production :
```bash
php bin/console cache:clear --env=prod
```

## Alternative : Script de déploiement

Pour éviter ce problème à l'avenir, ajoutez cette commande dans votre script de déploiement :

```bash
#!/bin/bash
# ... autres commandes de déploiement ...

# Vider le cache après déploiement
php bin/console cache:clear --env=prod --no-debug

# Régénérer le cache
php bin/console cache:warmup --env=prod
```

