@echo off
echo.
echo ========================================
echo   EduMaster - School Management System
echo   Installation Script for Windows
echo ========================================
echo.

echo [1/8] Verification des prerequis...
where composer >nul 2>&1
if %errorlevel% neq 0 (
    echo ERREUR: Composer n'est pas installe ou pas dans le PATH
    echo Veuillez installer Composer depuis https://getcomposer.org/
    pause
    exit /b 1
)

where php >nul 2>&1
if %errorlevel% neq 0 (
    echo ERREUR: PHP n'est pas installe ou pas dans le PATH
    echo Veuillez installer PHP 8.1+ depuis https://www.php.net/
    pause
    exit /b 1
)

echo ✓ Composer et PHP detectes

echo.
echo [2/8] Installation des dependances Composer...
composer install --optimize-autoloader
if %errorlevel% neq 0 (
    echo ERREUR: Echec de l'installation des dependances
    pause
    exit /b 1
)

echo.
echo [3/8] Configuration de l'environnement...
if not exist .env (
    copy .env.example .env
    echo ✓ Fichier .env cree depuis .env.example
) else (
    echo ✓ Fichier .env existe deja
)

echo.
echo [4/8] Generation de la cle d'application...
php artisan key:generate --ansi
if %errorlevel% neq 0 (
    echo ERREUR: Echec de la generation de la cle
    pause
    exit /b 1
)

echo.
echo [5/8] Creation des dossiers de stockage...
if not exist storage\logs mkdir storage\logs
if not exist storage\framework\cache mkdir storage\framework\cache
if not exist storage\framework\sessions mkdir storage\framework\sessions
if not exist storage\framework\views mkdir storage\framework\views
if not exist storage\app\public mkdir storage\app\public
echo ✓ Dossiers de stockage crees

echo.
echo [6/8] Configuration des permissions...
php artisan storage:link
if %errorlevel% neq 0 (
    echo ATTENTION: Echec de la creation du lien symbolique
    echo Vous devrez peut-etre executer cette commande manuellement
)

echo.
echo [7/8] Configuration de la base de donnees...
echo.
echo IMPORTANT: Avant de continuer, assurez-vous que:
echo - Votre serveur MySQL/PostgreSQL est demarre
echo - Vous avez cree une base de donnees pour EduMaster
echo - Vous avez configure les parametres DB dans le fichier .env
echo.
set /p continue="Voulez-vous continuer avec les migrations? (o/n): "
if /i "%continue%"=="o" (
    echo Execution des migrations...
    php artisan migrate --seed --force
    if %errorlevel% neq 0 (
        echo ERREUR: Echec des migrations
        echo Verifiez votre configuration de base de donnees dans .env
        pause
        exit /b 1
    )
    echo ✓ Base de donnees initialisee avec succes
) else (
    echo Migration ignoree. Vous devrez executer 'php artisan migrate --seed' manuellement
)

echo.
echo [8/8] Configuration finale...
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo.
echo ========================================
echo   Installation terminee avec succes!
echo ========================================
echo.
echo Comptes par defaut crees:
echo - Admin: admin@edumaster.mg / password
echo - Directeur: director@edumaster.mg / password  
echo - Secretaire: secretary@edumaster.mg / password
echo - Professeur: teacher@edumaster.mg / password
echo - Parent: parent@edumaster.mg / password
echo.
echo Pour demarrer le serveur de developpement:
echo   php artisan serve
echo.
echo Puis ouvrez votre navigateur sur: http://127.0.0.1:8000
echo.
echo Documentation complete: README.md
echo Support: https://github.com/your-repo/edumaster
echo.
pause
