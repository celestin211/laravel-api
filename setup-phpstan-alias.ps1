# Script pour creer un alias PowerShell pour phpstan.ps1
# Usage: .\setup-phpstan-alias.ps1
# Puis vous pourrez utiliser: phpstan analyse --level=8 --memory-limit=512M app

# Creer l'alias dans le profil PowerShell actuel
$profilePath = $PROFILE.CurrentUserAllHosts

# Verifier si le profil existe
if (-not (Test-Path $profilePath)) {
    New-Item -Path $profilePath -ItemType File -Force | Out-Null
    Write-Host "Profil PowerShell cree: $profilePath" -ForegroundColor Green
}

# Lire le contenu actuel du profil
$profileContent = Get-Content $profilePath -ErrorAction SilentlyContinue

# Chemin absolu vers phpstan.ps1
$phpstanScriptPath = Join-Path (Get-Location).Path "phpstan.ps1"

# Verifier si l'alias existe deja
$aliasExists = $profileContent | Select-String -Pattern "function phpstan"

if (-not $aliasExists) {
    # Ajouter la fonction au profil
    $functionDefinition = @"

# Alias pour phpstan.ps1
function phpstan {
    & "$phpstanScriptPath" `$args
}

"@
    
    Add-Content -Path $profilePath -Value $functionDefinition
    Write-Host "Alias 'phpstan' ajoute au profil PowerShell" -ForegroundColor Green
    Write-Host "Rechargez votre session PowerShell ou executez: . `$PROFILE" -ForegroundColor Yellow
} else {
    Write-Host "L'alias 'phpstan' existe deja dans le profil" -ForegroundColor Yellow
}









