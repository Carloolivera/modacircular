# 📦 Instalación de ModaCircular

## 🚀 Pasos de instalación

### 1. Levantar Docker

```bash
docker compose up -d --build
```

Esto hará **automáticamente**:
- ✅ Instalar dependencias de Composer (incluyendo Filament y Breeze)
- ✅ Generar APP_KEY
- ✅ Ejecutar migraciones (crear tablas)
- ✅ Ejecutar seeders (datos iniciales)
- ✅ Crear storage link

### 2. Instalar dependencias de NPM

```bash
docker compose exec app npm install
```

### 3. Compilar assets de Filament

```bash
docker compose exec app npm run build
```

### 4. Publicar assets de Filament (opcional)

```bash
docker compose exec app php artisan filament:assets
```

---

## 🌐 Accesos

| Servicio | URL | Credenciales |
|----------|-----|--------------|
| **Web Pública** | http://localhost:8000 | - |
| **Panel Admin (Filament)** | http://localhost:8000/admin | admin@modacircular.com / admin123 |
| **MySQL** | localhost:3306 | modacircular / password |

---

## 📊 Panel Admin - Filament

El panel admin incluye:

### ✅ Categorías
- CRUD completo de categorías
- Activar/desactivar categorías
- Ver cantidad de productos por categoría

### ✅ Productos
- CRUD completo de productos
- Gestión de imágenes (múltiples imágenes por producto)
- Control de stock (se oculta automáticamente cuando stock = 0)
- Productos destacados
- Filtros por categoría, visibilidad, stock
- Talles y colores

### ✅ Configuraciones
- WhatsApp (número, mensaje template)
- Envíos (moto, retiro personalizado, costos)
- Pagos (Mercado Pago, transferencia, efectivo, CBU, alias)

---

## 🗄️ Base de Datos

La base de datos **ya está creada** con las siguientes tablas:

- `users` - Usuarios (admin creado automáticamente)
- `categories` - 6 categorías iniciales
- `products` - Productos del catálogo
- `product_images` - Imágenes de productos
- `settings` - Configuraciones del sistema

---

## 🔧 Comandos útiles

### Acceder al contenedor
```bash
docker compose exec app bash
```

### Ver logs
```bash
docker compose logs -f app
```

### Limpiar cache de Laravel
```bash
docker compose exec app php artisan cache:clear
docker compose exec app php artisan config:clear
docker compose exec app php artisan view:clear
```

### Recrear la base de datos
```bash
docker compose exec app php artisan migrate:fresh --seed
```

### Crear un nuevo usuario admin
```bash
docker compose exec app php artisan tinker
User::create(['name' => 'Admin', 'email' => 'admin@ejemplo.com', 'password' => bcrypt('password')]);
```

---

## ✨ Próximos pasos

1. ✅ Panel Admin (Filament) - **COMPLETADO**
2. ⏳ Laravel Breeze (Autenticación) - Pendiente
3. ⏳ Vistas del catálogo público - Pendiente
4. ⏳ Carrito de compras - Pendiente
5. ⏳ Integración con WhatsApp - Pendiente

---

## 📝 Notas

- El panel admin está en `/admin`
- Las credenciales por defecto son: `admin@modacircular.com` / `admin123`
- Los productos se ocultan automáticamente cuando el stock llega a 0
- Las imágenes se suben a `storage/app/public/products`
- Filament usa Tailwind CSS y Livewire
