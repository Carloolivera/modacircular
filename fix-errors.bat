@echo off
echo ================================================
echo Solucionando errores comunes de ModaCircular
echo ================================================
echo.

echo 1. Verificando archivo .env...
docker compose exec -T app test -f .env

echo.
echo 2. Generando APP_KEY...
docker compose exec -T app php artisan key:generate --force

echo.
echo 3. Verificando conexion a base de datos...
docker compose exec -T app php artisan db:show

echo.
echo 4. Ejecutando migraciones...
docker compose exec -T app php artisan migrate --force

echo.
echo 5. Ejecutando seeders...
docker compose exec -T app php artisan db:seed --force

echo.
echo 6. Configurando permisos de storage...
docker compose exec -T app chmod -R 775 storage bootstrap/cache
docker compose exec -T app chown -R www-data:www-data storage bootstrap/cache

echo.
echo 7. Creando storage link...
docker compose exec -T app php artisan storage:link

echo.
echo 8. Limpiando cache...
docker compose exec -T app php artisan config:clear
docker compose exec -T app php artisan cache:clear
docker compose exec -T app php artisan route:clear
docker compose exec -T app php artisan view:clear

echo.
echo 9. Instalando dependencias npm...
docker compose exec -T app npm install

echo.
echo 10. Compilando assets...
docker compose exec -T app npm run build

echo.
echo ================================================
echo Proceso completado!
echo ================================================
echo.
echo Ahora intenta acceder a: http://localhost:8000
echo.
echo Si sigue sin funcionar, ejecuta:
echo   docker compose logs app
echo.
pause
