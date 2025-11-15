#!/bin/bash

set -e

echo "🚀 Iniciando ModaCircular..."

# Esperar a que MySQL esté listo
echo "⏳ Esperando a que MySQL esté listo..."
until mysql -h"${DB_HOST}" -u"${DB_USERNAME}" -p"${DB_PASSWORD}" "${DB_DATABASE}" --skip-ssl -e "SELECT 1" > /dev/null 2>&1; do
    echo "⏳ MySQL no está listo todavía, reintentando en 3 segundos..."
    sleep 3
done

echo "✅ MySQL está listo!"

# Instalar dependencias de Composer (solo si no existen)
if [ ! -d "vendor" ]; then
    echo "📦 Instalando dependencias de Composer..."
    composer install --no-interaction --no-dev --optimize-autoloader
else
    echo "✅ Dependencias de Composer ya instaladas"
fi

# Generar APP_KEY si no existe
if ! grep -q "APP_KEY=base64:" .env 2>/dev/null; then
    echo "🔑 Generando APP_KEY..."
    php artisan key:generate --ansi
fi

# Configurar permisos antes de migrar
echo "🔒 Configurando permisos..."
chmod -R 775 storage bootstrap/cache 2>/dev/null || true
chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || true

# Ejecutar migraciones (con manejo de errores)
echo "🗄️  Ejecutando migraciones..."
php artisan migrate --force || echo "⚠️  Error en migraciones, continuando..."

# Verificar si necesitamos ejecutar seeders
SETTINGS_COUNT=$(php artisan tinker --execute="echo \App\Models\Setting::count();" 2>/dev/null | tail -n 1)
if [ "$SETTINGS_COUNT" = "0" ] || [ -z "$SETTINGS_COUNT" ]; then
    echo "🌱 Ejecutando seeders..."
    php artisan db:seed --force || echo "⚠️  Error en seeders, continuando..."
fi

# Crear storage link si no existe
if [ ! -L "public/storage" ]; then
    echo "🔗 Creando storage link..."
    php artisan storage:link
fi

echo ""
echo "✅ ¡ModaCircular iniciado correctamente!"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "🌐 Web: http://localhost:8000"
echo "🗄️  phpMyAdmin: http://localhost:8081"
echo "👤 Admin: admin@modacircular.com / admin123"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""

# Iniciar PHP-FPM
exec docker-php-entrypoint "$@"
