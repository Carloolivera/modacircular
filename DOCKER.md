# 🐳 ModaCircular - Guía de Docker

## 📋 Requisitos Previos

- Docker instalado
- Docker Compose instalado
- Puerto 8000, 8081 y 3306 disponibles

## 🚀 Inicio Rápido

### 1. Clonar o actualizar el repositorio

```bash
git pull origin claude/clothing-store-whatsapp-011NhnYeo3Qe6CxMXt9fZiqi
```

### 2. Levantar los contenedores

```bash
docker compose up -d --build
```

**Esto hará automáticamente:**
- ✅ Instalar dependencias de Composer
- ✅ Generar APP_KEY
- ✅ Ejecutar migraciones (crear tablas en MySQL)
- ✅ Ejecutar seeders (datos iniciales)
- ✅ Crear storage link
- ✅ Configurar permisos

### 3. Ver los logs (opcional)

```bash
docker compose logs -f app
```

Deberías ver:
```
✅ ¡ModaCircular iniciado correctamente!
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
🌐 Web: http://localhost:8000
🗄️  phpMyAdmin: http://localhost:8081
👤 Admin: admin@modacircular.com / admin123
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
```

## 🌐 Accesos

| Servicio | URL | Credenciales |
|----------|-----|--------------|
| **Aplicación Web** | http://localhost:8000 | - |
| **phpMyAdmin** | http://localhost:8081 | Usuario: `root` / Contraseña: `root` |
| **MySQL** | localhost:3306 | Usuario: `modacircular` / Contraseña: `password` |
| **Admin Panel** | http://localhost:8000/login | admin@modacircular.com / admin123 |

## 📊 Base de Datos

### Información de conexión:
- **Host**: `db` (interno) o `localhost` (externo)
- **Puerto**: 3306
- **Base de datos**: `modacircular`
- **Usuario**: `modacircular`
- **Contraseña**: `password`
- **Root**: `root` / `root`

### Tablas creadas automáticamente:
1. `users` - Usuarios del sistema
2. `categories` - Categorías de productos
3. `products` - Productos del catálogo
4. `product_images` - Imágenes de productos
5. `settings` - Configuraciones del sistema
6. Tablas de Laravel (migrations, cache, jobs, sessions, etc.)

### Datos iniciales (seeders):
- **6 categorías**: Remeras, Pantalones, Vestidos, Buzos y Sweaters, Camperas, Accesorios
- **Configuraciones**: WhatsApp, envíos, métodos de pago
- **Usuario admin**: admin@modacircular.com / admin123

## 🛠️ Comandos Útiles

### Ver estado de los contenedores
```bash
docker compose ps
```

### Detener los contenedores
```bash
docker compose down
```

### Detener y eliminar volúmenes (⚠️ elimina la base de datos)
```bash
docker compose down -v
```

### Reiniciar un servicio específico
```bash
docker compose restart app
docker compose restart db
docker compose restart webserver
```

### Ejecutar comandos Artisan
```bash
docker compose exec app php artisan [comando]
```

Ejemplos:
```bash
# Ver rutas
docker compose exec app php artisan route:list

# Limpiar cache
docker compose exec app php artisan cache:clear

# Crear un modelo
docker compose exec app php artisan make:model NombreModelo

# Ejecutar migraciones manualmente
docker compose exec app php artisan migrate

# Ejecutar seeders manualmente
docker compose exec app php artisan db:seed
```

### Acceder al contenedor
```bash
docker compose exec app bash
```

### Ver logs
```bash
# Todos los servicios
docker compose logs -f

# Solo app
docker compose logs -f app

# Solo MySQL
docker compose logs -f db
```

## 🔧 Solución de Problemas

### Error: Puerto 8000 ya está en uso
```bash
# Cambiar el puerto en docker-compose.yml línea 56:
ports:
  - "8001:80"  # Cambiar 8000 por 8001
```

### Error: Puerto 3306 ya está en uso
```bash
# Cambiar el puerto en docker-compose.yml línea 15:
ports:
  - "3307:3306"  # Cambiar 3306 por 3307
```

### Reconstruir contenedores desde cero
```bash
docker compose down -v
docker compose build --no-cache
docker compose up -d
```

### Permisos de storage
```bash
docker compose exec app chmod -R 775 storage bootstrap/cache
docker compose exec app chown -R www-data:www-data storage bootstrap/cache
```

### Resetear base de datos
```bash
docker compose exec app php artisan migrate:fresh --seed
```

## 📦 Estructura de Contenedores

```
┌─────────────────┐
│   webserver     │ (Nginx - Puerto 8000)
│  nginx:alpine   │
└────────┬────────┘
         │
┌────────▼────────┐
│      app        │ (Laravel/PHP-FPM)
│   PHP 8.2-fpm   │
└────────┬────────┘
         │
┌────────▼────────┐
│       db        │ (MySQL - Puerto 3306)
│   MySQL 8.0     │
└────────┬────────┘
         │
┌────────▼────────┐
│   phpmyadmin    │ (phpMyAdmin - Puerto 8081)
│   phpmyadmin    │
└─────────────────┘
```

## 🔄 Actualizar el proyecto

```bash
# Hacer pull de los cambios
git pull origin claude/clothing-store-whatsapp-011NhnYeo3Qe6CxMXt9fZiqi

# Reconstruir contenedores
docker compose up -d --build

# Las migraciones y seeders se ejecutan automáticamente
```

## 📝 Notas

- Las migraciones se ejecutan **automáticamente** al iniciar el contenedor
- Los seeders solo se ejecutan si la base de datos está vacía
- El storage link se crea automáticamente
- Los permisos se configuran automáticamente
- Todos los archivos están sincronizados con el contenedor (volumen)
