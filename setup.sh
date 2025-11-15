#!/bin/bash

echo "🚀 Setup de ModaCircular"
echo "========================"
echo ""

# Verificar si Docker está corriendo
if ! docker info > /dev/null 2>&1; then
    echo "❌ Docker no está corriendo. Por favor inicia Docker Desktop."
    exit 1
fi

echo "✅ Docker está corriendo"
echo ""

# Levantar contenedores
echo "📦 Levantando contenedores..."
docker compose up -d --build

echo ""
echo "⏳ Esperando a que los contenedores estén listos (30 segundos)..."
sleep 30

# Instalar dependencias de Node
echo ""
echo "📦 Instalando dependencias de Node.js..."
docker compose exec -T app npm install

# Compilar assets
echo ""
echo "🎨 Compilando assets (CSS y JS)..."
docker compose exec -T app npm run build

# Limpiar cache
echo ""
echo "🧹 Limpiando cache de Laravel..."
docker compose exec -T app php artisan config:clear
docker compose exec -T app php artisan cache:clear
docker compose exec -T app php artisan route:clear

echo ""
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "✅ ¡Setup completado!"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""
echo "🌐 Accede a:"
echo "   Sitio público: http://localhost:8000"
echo "   Panel admin:   http://localhost:8000/admin"
echo ""
echo "👤 Credenciales admin:"
echo "   Email:    admin@modacircular.com"
echo "   Password: admin123"
echo ""
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
