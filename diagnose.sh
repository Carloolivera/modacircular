#!/bin/bash

echo "🔍 Diagnóstico de ModaCircular"
echo "=============================="
echo ""

echo "📦 Estado de contenedores Docker:"
docker compose ps
echo ""

echo "🌐 Estado de servicios:"
echo "  - Web: http://localhost:8000"
echo "  - Admin: http://localhost:8000/admin"
echo ""

echo "📄 Verificando archivo .env:"
if docker compose exec -T app test -f .env; then
    echo "  ✅ .env existe"
    echo ""
    echo "  APP_KEY:"
    docker compose exec -T app grep "^APP_KEY=" .env || echo "  ❌ APP_KEY no configurada"
    echo ""
    echo "  Database:"
    docker compose exec -T app grep "^DB_" .env | head -5
else
    echo "  ❌ .env NO existe"
fi

echo ""
echo "🗄️ Estado de la base de datos:"
docker compose exec -T db mysql -uroot -proot -e "SHOW DATABASES;" 2>/dev/null | grep modacircular && echo "  ✅ Base de datos 'modacircular' existe" || echo "  ❌ Base de datos 'modacircular' NO existe"

echo ""
echo "📊 Tablas en la base de datos:"
docker compose exec -T db mysql -uroot -proot modacircular -e "SHOW TABLES;" 2>/dev/null

echo ""
echo "📝 Últimos 20 logs de la aplicación:"
docker compose logs --tail=20 app

echo ""
echo "=============================="
echo "Para ver logs en tiempo real:"
echo "  docker compose logs -f app"
echo ""
echo "Para reiniciar todo:"
echo "  docker compose restart"
echo ""
