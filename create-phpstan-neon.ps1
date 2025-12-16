# Script PowerShell pour creer phpstan.neon a partir de phpstan.neon.txt
# Usage: .\create-phpstan-neon.ps1

Write-Host "Creation du fichier phpstan.neon..." -ForegroundColor Cyan

if (-not (Test-Path "phpstan.neon.txt")) {
    Write-Host "Le fichier phpstan.neon.txt n'existe pas!" -ForegroundColor Red
    exit 1
}

# Copier le contenu de phpstan.neon.txt vers phpstan.neon
Get-Content "phpstan.neon.txt" | Set-Content "phpstan.neon" -Encoding UTF8

if (Test-Path "phpstan.neon") {
    Write-Host "Fichier phpstan.neon cree avec succes!" -ForegroundColor Green
    Write-Host ""
    Write-Host "Vous pouvez maintenant utiliser:" -ForegroundColor Yellow
    Write-Host "  vendor/bin/phpstan analyse --level=8 --memory-limit=512M app" -ForegroundColor White
} else {
    Write-Host "Erreur lors de la creation du fichier phpstan.neon" -ForegroundColor Red
    exit 1
}

