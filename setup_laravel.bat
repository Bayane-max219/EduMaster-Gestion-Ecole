@echo off
echo.
echo ========================================
echo   Configuration Laravel EduMaster
echo ========================================
echo.

cd /d "c:\Users\Miguel\Desktop\Application Novembre\Gestion_Ecole"

echo [1/6] Creation du fichier .env...
if exist .env (
    echo Fichier .env existe deja
) else (
    copy .env_config .env
    echo ✓ Fichier .env cree
)

echo.
echo [2/6] Installation des dependances Composer...
composer install --no-dev --optimize-autoloader
if %errorlevel% neq 0 (
    echo ERREUR: Echec de l'installation Composer
    pause
    exit /b 1
)

echo.
echo [3/6] Generation de la cle d'application...
php artisan key:generate --ansi
if %errorlevel% neq 0 (
    echo ERREUR: Echec de la generation de la cle
    pause
    exit /b 1
)

echo.
echo [4/6] Creation des dossiers de stockage...
if not exist storage\logs mkdir storage\logs
if not exist storage\framework\cache\data mkdir storage\framework\cache\data
if not exist storage\framework\sessions mkdir storage\framework\sessions
if not exist storage\framework\views mkdir storage\framework\views
if not exist storage\app\public mkdir storage\app\public
if not exist public\storage mkdir public\storage

echo.
echo [5/6] Configuration des permissions...
php artisan storage:link
if %errorlevel% neq 0 (
    echo ATTENTION: Echec du lien symbolique (normal sur certains systemes)
)

echo.
echo [6/6] Test de la connexion base de donnees...
php artisan migrate:status
if %errorlevel% neq 0 (
    echo ATTENTION: Probleme de connexion base de donnees
    echo Verifiez vos parametres MySQL dans .env
    echo.
    echo Parametres actuels:
    echo DB_HOST=127.0.0.1
    echo DB_PORT=3306
    echo DB_DATABASE=edumaster_school
    echo DB_USERNAME=root
    echo DB_PASSWORD=(vide)
    echo.
    echo Si votre mot de passe MySQL n'est pas vide, editez le fichier .env
    pause
)

echo.
echo ========================================
echo   Configuration terminee !
echo ========================================
echo.
echo Pour demarrer le serveur Laravel:
echo   php artisan serve
echo.
echo Puis ouvrez: http://127.0.0.1:8000
echo.
echo Comptes de test:
echo - admin@edumaster.mg / password
echo - director@edumaster.mg / password
echo - teacher@edumaster.mg / password
echo.
pause
