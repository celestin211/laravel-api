# Script PowerShell pour démarrer le serveur Laravel
# Usage: .\start-server.ps1

Write-Host "🔄 Arrêt des processus PHP existants sur le port 8000..." -ForegroundColor Yellow
$processes = Get-NetTCPConnection -LocalPort 8000 -ErrorAction SilentlyContinue | Select-Object -ExpandProperty OwningProcess -Unique
if ($processes) {
    $processes | ForEach-Object { Stop-Process -Id $_ -Force -ErrorAction SilentlyContinue }
    Start-Sleep -Seconds 2
}

Write-Host "🔍 Vérification de PHP..." -ForegroundColor Cyan
$phpVersion = php --version 2>&1
if ($LASTEXITCODE -ne 0) {
    Write-Host "❌ PHP n'est pas installé ou n'est pas dans le PATH" -ForegroundColor Red
    exit 1
}
Write-Host "✅ PHP détecté: $($phpVersion -split "`n" | Select-Object -First 1)" -ForegroundColor Green

Write-Host "🔍 Vérification de Laravel..." -ForegroundColor Cyan
if (-not (Test-Path "artisan")) {
    Write-Host "❌ Le fichier artisan n'existe pas. Assurez-vous d'être dans le répertoire du projet Laravel." -ForegroundColor Red
    exit 1
}
Write-Host "✅ Laravel détecté" -ForegroundColor Green

Write-Host ""
Write-Host "🚀 Démarrage du serveur Laravel sur http://127.0.0.1:8000..." -ForegroundColor Cyan
Write-Host "⚠️  Appuyez sur Ctrl+C pour arrêter le serveur" -ForegroundColor Yellow
Write-Host ""

# Démarrer le serveur
php artisan serve --host=127.0.0.1 --port=8000






