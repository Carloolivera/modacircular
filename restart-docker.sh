#!/bin/bash

echo "🔄 Reiniciando Docker para ModaCircular"
echo "========================================"
echo ""

# Verificar si Docker está corriendo
if ! docker info > /dev/null 2>&1; then
    echo "❌ Docker no está corriendo. Por favor inicia Docker Desktop."
    exit 1
fi

echo "✅ Docker está corriendo"
echo ""

# Detener contenedores
echo "🛑 Deteniendo contenedores..."
docker compose down

# Preguntar si desea limpiar volúmenes
read -p "¿Deseas eliminar el volumen de MySQL? (esto borrará los datos) [y/N]: " -n 1 -r
echo ""
if [[ $REPLY =~ ^[Yy]$ ]]; then
    echo "🗑️  Eliminando volumen de MySQL..."
    docker volume rm modacircular_mysql-data 2>/dev/null || true
fi

# Reconstruir y levantar contenedores
echo ""
echo "🔨 Reconstruyendo contenedores..."
docker compose build --no-cache

echo ""
echo "🚀 Levantando contenedores..."
docker compose up -d

echo ""
echo "⏳ Esperando a que los contenedores estén listos..."
echo "   (Esto puede tomar hasta 2 minutos)"

# Esperar hasta que la app esté lista
sleep 10

# Mostrar logs
echo ""
echo "📋 Mostrando logs (presiona Ctrl+C para salir)..."
echo ""
docker compose logs -f app
