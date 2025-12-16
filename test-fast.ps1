# Script PowerShell pour exécuter les tests rapides (sans Browser)
# Usage: .\test-fast.ps1

Write-Host "🧪 Exécution des tests rapides (Unit + Feature)..." -ForegroundColor Cyan
Write-Host "⚠️  Note: ParaTest ne fonctionne pas sur Windows, exécution séquentielle" -ForegroundColor Yellow
php artisan test --exclude-testsuite=Browser

