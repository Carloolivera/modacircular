# Solución de Problemas - ModaCircular

## Error 502 Bad Gateway

### Síntomas
- Al acceder a `http://localhost:8000` aparece: "502 Bad Gateway nginx/1.29.3"
- Los logs de Docker muestran: "⏳ MySQL no está listo todavía, reintentando en 3 segundos..."

### Causa
MySQL no está inicializando correctamente o no está aceptando conexiones.

### Solución

#### Opción 1: Reinicio Rápido
```bash
chmod +x restart-docker.sh
./restart-docker.sh
```

Cuando se te pregunte si deseas eliminar el volumen de MySQL:
- Responde `y` si quieres empezar con una base de datos limpia
- Responde `n` si quieres mantener tus datos

#### Opción 2: Reinicio Manual
```bash
# 1. Detener todos los contenedores
docker compose down

# 2. (Opcional) Eliminar el volumen de MySQL si está corrupto
docker volume rm modacircular_mysql-data

# 3. Reconstruir contenedores
docker compose build --no-cache

# 4. Iniciar contenedores
docker compose up -d

# 5. Ver logs
docker compose logs -f app
```

#### Opción 3: Setup Completo
```bash
chmod +x setup.sh
./setup.sh
```

### Verificación

Una vez que los contenedores estén corriendo, deberías ver en los logs:
```
✅ MySQL está listo!
🗄️  Ejecutando migraciones...
✅ ¡ModaCircular iniciado correctamente!
```

Luego podrás acceder a:
- Sitio web: http://localhost:8000
- Panel admin: http://localhost:8000/admin

## Otros Problemas Comunes

### Docker no está corriendo
**Síntoma:** Error al ejecutar comandos docker
**Solución:** Inicia Docker Desktop

### Puerto 8000 ya está en uso
**Síntoma:** Error "port is already allocated"
**Solución:**
```bash
# Detener otros servicios en el puerto 8000
docker compose down
# o cambiar el puerto en docker-compose.yml
```

### Permisos en archivos
**Síntoma:** Errores de permisos al escribir archivos
**Solución:**
```bash
docker compose exec app chmod -R 775 storage bootstrap/cache
docker compose exec app chown -R www-data:www-data storage bootstrap/cache
```

### Cache de Laravel
**Síntoma:** Cambios no se reflejan
**Solución:**
```bash
docker compose exec app php artisan config:clear
docker compose exec app php artisan cache:clear
docker compose exec app php artisan route:clear
docker compose exec app php artisan view:clear
```

## Comandos Útiles

### Ver logs en tiempo real
```bash
# Todos los contenedores
docker compose logs -f

# Solo la aplicación
docker compose logs -f app

# Solo MySQL
docker compose logs -f db

# Solo Nginx
docker compose logs -f webserver
```

### Entrar a un contenedor
```bash
# Contenedor de la aplicación
docker compose exec app bash

# Contenedor de MySQL
docker compose exec db bash
```

### Verificar estado de contenedores
```bash
docker compose ps
```

### Ejecutar comandos artisan
```bash
docker compose exec app php artisan [comando]
```

### Reinstalar dependencias
```bash
docker compose exec app composer install
docker compose exec app npm install
```
