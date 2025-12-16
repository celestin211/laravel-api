@echo off
REM Script batch pour lancer PHPStan avec la configuration automatique
REM Usage: phpstan.bat [arguments PHPStan]
REM Exemple: phpstan.bat analyse --level=8 --memory-limit=512M app

setlocal

REM Determiner quel fichier de configuration utiliser
if exist "phpstan.neon" (
    set CONFIG_FILE=phpstan.neon
    goto :run
)
if exist "phpstan.neon.yaml" (
    set CONFIG_FILE=phpstan.neon.yaml
    goto :run
)
if exist "phpstan.neon.txt" (
    set CONFIG_FILE=phpstan.neon.txt
    goto :run
)

REM Si aucun fichier de configuration trouve, lancer sans configuration
:run
if "%CONFIG_FILE%"=="" (
    vendor\bin\phpstan %*
) else (
    REM Verifier si --configuration est deja specifie
    echo %* | findstr /C:"--configuration" >nul
    if errorlevel 1 (
        vendor\bin\phpstan %* --configuration=%CONFIG_FILE%
    ) else (
        vendor\bin\phpstan %*
    )
)

endlocal




