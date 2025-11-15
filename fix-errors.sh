#!/bin/bash

echo "🔧 Solucionando errores comunes de ModaCircular"
echo "================================================"
echo ""

# 1. Verificar que .env existe
echo "1️⃣ Verificando archivo .env..."
docker compose exec -T app test -f .env && echo "✅ .env existe" || echo "❌ .env NO existe"

# 2. Generar APP_KEY
echo ""
echo "2️⃣ Generando APP_KEY..."
docker compose exec -T app php artisan key:generate --force

# 3. Verificar conexión a base de datos
echo ""
echo "3️⃣ Verificando conexión a base de datos..."
docker compose exec -T app php artisan db:show

# 4. Ejecutar migraciones
echo ""
echo "4️⃣ Ejecutando migraciones..."
docker compose exec -T app php artisan migrate --force

# 5. Ejecutar seeders
echo ""
echo "5️⃣ Ejecutando seeders..."
docker compose exec -T app php artisan db:seed --force

# 6. Configurar permisos
echo ""
echo "6️⃣ Configurando permisos de storage..."
docker compose exec -T app chmod -R 775 storage bootstrap/cache
docker compose exec -T app chown -R www-data:www-data storage bootstrap/cache

# 7. Crear storage link
echo ""
echo "7️⃣ Creando storage link..."
docker compose exec -T app php artisan storage:link

# 8. Limpiar cache
echo ""
echo "8️⃣ Limpiando cache..."
docker compose exec -T app php artisan config:clear
docker compose exec -T app php artisan cache:clear
docker compose exec -T app php artisan route:clear
docker compose exec -T app php artisan view:clear

# 9. Instalar dependencias npm
echo ""
echo "9️⃣ Instalando dependencias npm..."
docker compose exec -T app npm install

# 10. Compilar assets
echo ""
echo "🔟 Compilando assets..."
docker compose exec -T app npm run build

echo ""
echo "================================================"
echo "✅ ¡Proceso completado!"
echo "================================================"
echo ""
echo "Ahora intenta acceder a: http://localhost:8000"
echo ""
echo "Si sigue sin funcionar, ejecuta:"
echo "  docker compose logs app"
echo ""
