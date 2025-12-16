# Script pour créer le fichier server.php nécessaire pour Laravel
# Usage: .\create-server-php.ps1

$serverPhpContent = @'
<?php

/**
 * Laravel - A PHP Framework For Web Artisans
 *
 * @package  Laravel
 * @author   Taylor Otwell <taylor@laravel.com>
 */

$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? ''
);

// This file allows us to emulate Apache's "mod_rewrite" functionality from the
// built-in PHP web server. This provides a convenient way to test a Laravel
// application without having installed a "real" web server software here.
if ($uri !== '/' && file_exists(__DIR__.'/public'.$uri)) {
    return false;
}

require_once __DIR__.'/public/index.php';
'@

if (Test-Path "server.php") {
    Write-Host "✅ Le fichier server.php existe déjà" -ForegroundColor Green
} else {
    try {
        Set-Content -Path "server.php" -Value $serverPhpContent -ErrorAction Stop
        Write-Host "✅ Fichier server.php créé avec succès!" -ForegroundColor Green
    } catch {
        Write-Host "❌ Erreur lors de la création du fichier: $_" -ForegroundColor Red
        Write-Host ""
        Write-Host "Veuillez créer manuellement le fichier server.php avec le contenu suivant:" -ForegroundColor Yellow
        Write-Host ""
        Write-Host $serverPhpContent -ForegroundColor Gray
        exit 1
    }
}

