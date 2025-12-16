# Script PowerShell pour lancer PHPStan avec la configuration automatique
# Usage: .\phpstan.ps1 [arguments PHPStan]
# Exemple: .\phpstan.ps1 analyse --level=8 --memory-limit=512M app

param(
    [Parameter(ValueFromRemainingArguments=$true)]
    [string[]]$PhpStanArgs
)

# Determiner quel fichier de configuration utiliser
$configFile = $null
if (Test-Path "phpstan.neon") {
    $configFile = "phpstan.neon"
} elseif (Test-Path "phpstan.neon.yaml") {
    $configFile = "phpstan.neon.yaml"
} elseif (Test-Path "phpstan.neon.txt") {
    $configFile = "phpstan.neon.txt"
}

# Si aucun argument n'est fourni, utiliser les arguments par defaut
if ($PhpStanArgs.Count -eq 0) {
    $PhpStanArgs = @("analyse", "--level=8", "--memory-limit=512M", "app")
}

# Construire la commande
$command = "vendor/bin/phpstan"

# Ajouter les arguments
foreach ($arg in $PhpStanArgs) {
    $command += " $arg"
}

# Si un fichier de configuration existe et que --configuration n'est pas deja specifie
if ($configFile -and ($PhpStanArgs -notmatch '--configuration')) {
    # Si le fichier est .neon, l'utiliser directement
    if ($configFile -match '\.neon$') {
        $command += " --configuration=$configFile"
    } else {
        # Pour .yaml ou .txt, creer un fichier temporaire .neon
        $tempDir = [System.IO.Path]::GetTempPath()
        $tempConfig = Join-Path $tempDir "phpstan_config_$(Get-Random).neon"
        
        try {
            $projectRoot = (Get-Location).Path -replace '\\', '/'
            $lines = Get-Content $configFile -Encoding UTF8
            $newContent = @()
            
            foreach ($line in $lines) {
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
            
            $utf8NoBom = New-Object System.Text.UTF8Encoding $false
            [System.IO.File]::WriteAllLines($tempConfig, $newContent, $utf8NoBom)
            
            $command += " --configuration=$tempConfig"
            
            # Executer la commande et capturer la sortie
            $output = Invoke-Expression $command 2>&1
            $exitCode = $LASTEXITCODE
            
            # Afficher la sortie (filtrer les messages PowerShell parasites)
            $output | Where-Object { $_ -notmatch 'CategoryInfo|FullyQualifiedErrorId|Au caractère' } | Write-Host
            
            # Nettoyer
            Remove-Item -Path $tempConfig -Force -ErrorAction SilentlyContinue
            
            exit $exitCode
        } catch {
            Write-Host "Erreur lors de la creation de la configuration temporaire: $_" -ForegroundColor Red
            # Executer sans configuration
            Invoke-Expression $command
            exit $LASTEXITCODE
        }
    }
} else {
    # Executer la commande telle quelle et capturer la sortie
    $output = Invoke-Expression $command 2>&1
    $exitCode = $LASTEXITCODE
    
    # Afficher la sortie (filtrer les messages PowerShell parasites)
    $output | Where-Object { $_ -notmatch 'CategoryInfo|FullyQualifiedErrorId|Au caractère' } | Write-Host
    
    exit $exitCode
}

