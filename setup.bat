@echo off
echo ============================================
echo Setup de ModaCircular
echo ============================================
echo.

echo Levantando contenedores...
docker compose up -d --build

echo.
echo Esperando a que los contenedores esten listos (30 segundos)...
timeout /t 30 /nobreak

echo.
echo Instalando dependencias de Node.js...
docker compose exec -T app npm install

echo.
echo Compilando assets (CSS y JS)...
docker compose exec -T app npm run build

echo.
echo Limpiando cache de Laravel...
docker compose exec -T app php artisan config:clear
docker compose exec -T app php artisan cache:clear
docker compose exec -T app php artisan route:clear

echo.
echo ============================================
echo Setup completado!
echo ============================================
echo.
echo Accede a:
echo   Sitio publico: http://localhost:8000
echo   Panel admin:   http://localhost:8000/admin
echo.
echo Credenciales admin:
echo   Email:    admin@modacircular.com
echo   Password: admin123
echo.
echo ============================================
pause
