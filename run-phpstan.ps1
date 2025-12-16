# Script PowerShell pour lancer PHPStan avec level=8
# Usage: .\run-phpstan.ps1
# Ce script utilise automatiquement phpstan.neon, phpstan.neon.yaml ou phpstan.neon.txt

Write-Host "Lancement de PHPStan avec level=8..." -ForegroundColor Cyan

# Determiner quel fichier de configuration utiliser
$configFile = $null
if (Test-Path "phpstan.neon") {
    $configFile = "phpstan.neon"
    Write-Host "Utilisation de phpstan.neon..." -ForegroundColor Green
} elseif (Test-Path "phpstan.neon.yaml") {
    $configFile = "phpstan.neon.yaml"
    Write-Host "Utilisation de phpstan.neon.yaml..." -ForegroundColor Green
} elseif (Test-Path "phpstan.neon.txt") {
    $configFile = "phpstan.neon.txt"
    Write-Host "Utilisation de phpstan.neon.txt..." -ForegroundColor Yellow
    Write-Host "Note: Pour une meilleure compatibilite, renommez phpstan.neon.txt en phpstan.neon" -ForegroundColor Yellow
    Write-Host "Commande: Rename-Item phpstan.neon.txt phpstan.neon" -ForegroundColor Yellow
    Write-Host ""
}

if ($configFile) {
    # Si le fichier est .neon, l'utiliser directement
    if ($configFile -match '\.neon$') {
        Write-Host "Lancement avec configuration: $configFile" -ForegroundColor Cyan
        vendor/bin/phpstan analyse --configuration=$configFile --memory-limit=512M app
        exit $LASTEXITCODE
    } else {
        # Pour .yaml ou .txt, creer un fichier temporaire .neon avec chemins absolus
        $tempDir = [System.IO.Path]::GetTempPath()
        $tempConfig = Join-Path $tempDir "phpstan_config_$(Get-Random).neon"
        
        try {
            # Lire le contenu ligne par ligne pour eviter les problemes de BOM
            $projectRoot = (Get-Location).Path -replace '\\', '/'
            $lines = Get-Content $configFile -Encoding UTF8
            $newContent = @()
            
            foreach ($line in $lines) {
                # Remplacer les chemins relatifs par des chemins absolus
                if ($line -match '^\s*-\s*vendor/larastan') {
                    $newContent += "    - $projectRoot/vendor/larastan/larastan/extension.neon"
                } elseif ($line -match '^\s*-\s*app\s*$') {
                    $newContent += "        - $projectRoot/app"
                } elseif ($line -match '^\s*-\s*database/factories\s*$') {
                    $newContent += "        - $projectRoot/database/factories"
                } elseif ($line -match '^\s*-\s*database/seeders\s*$') {
                    $newContent += "        - $projectRoot/database/seeders"
                } else {
                    $newContent += $line
                }
            }
            
            # Ecrire sans BOM
            $utf8NoBom = New-Object System.Text.UTF8Encoding $false
            [System.IO.File]::WriteAllLines($tempConfig, $newContent, $utf8NoBom)
            
            Write-Host "Configuration temporaire creee: $tempConfig" -ForegroundColor Gray
            
            # Lancer PHPStan et capturer la sortie
            $output = vendor/bin/phpstan analyse --configuration=$tempConfig --memory-limit=512M app 2>&1
            $exitCode = $LASTEXITCODE
            
            # Afficher la sortie (filtrer les messages PowerShell parasites)
            $output | Where-Object { $_ -notmatch 'CategoryInfo|FullyQualifiedErrorId' } | Write-Host
            
            # Nettoyer
            Remove-Item -Path $tempConfig -Force -ErrorAction SilentlyContinue
            
            exit $exitCode
        } catch {
            Write-Host "Erreur lors de la creation de la configuration temporaire: $_" -ForegroundColor Red
            Write-Host "Tentative sans fichier de configuration (ignoreErrors ne seront pas appliques)..." -ForegroundColor Yellow
            
            # Fallback: essayer sans fichier de configuration (utilise les valeurs par defaut)
            vendor/bin/phpstan analyse --level=8 --memory-limit=512M app
            exit $LASTEXITCODE
        }
    }
} else {
    Write-Host "Aucun fichier de configuration trouve (phpstan.neon, phpstan.neon.yaml ou phpstan.neon.txt)" -ForegroundColor Red
    Write-Host "Lancement sans configuration (ignoreErrors ne seront pas appliques)..." -ForegroundColor Yellow
    vendor/bin/phpstan analyse --level=8 --memory-limit=512M app
    exit $LASTEXITCODE
}

