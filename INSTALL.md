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

---

## 🛍️ Catálogo Público

El sitio público incluye:

### ✅ Página Principal (/)
- Hero section con llamado a la acción
- Categorías disponibles
- Productos destacados
- Links rápidos a productos

### ✅ Listado de Productos (/productos)
- Grid responsivo de productos
- Filtros por categoría
- Búsqueda de productos
- Ordenamiento (más nuevos, precio, nombre)
- Paginación

### ✅ Detalle de Producto (/producto/{slug})
- Galería de imágenes con thumbnails
- Información completa (precio, stock, talle, color)
- Cantidad ajustable
- Productos relacionados
- Botón para agregar al carrito

### ✅ Vista por Categoría (/categoria/{slug})
- Productos filtrados por categoría
- Grid responsivo
- Paginación

---

## 🛒 Carrito de Compras

### ✅ Funcionalidades del Carrito
- Agregar productos desde cualquier vista
- Ajustar cantidades
- Eliminar productos
- Persistencia en sesión
- Validación de stock en tiempo real
- Contador en navegación

### ✅ Proceso de Checkout
1. Datos del cliente (nombre, teléfono)
2. Método de envío:
   - Envío en moto (requiere dirección)
   - Retiro en persona
3. Método de pago:
   - Mercado Pago
   - Transferencia bancaria
   - Efectivo
4. Notas adicionales (opcional)

### ✅ Integración WhatsApp
- Mensaje formateado automáticamente con:
  * Datos del cliente
  * Lista de productos con cantidades y precios
  * Total del pedido
  * Método de envío y dirección
  * Método de pago
  * Notas adicionales
- Redirección automática a WhatsApp Web
- Carrito se vacía después del envío

---

## 🎨 Diseño y UX

- **Framework CSS**: Tailwind CSS
- **JavaScript**: Alpine.js para interactividad
- **Diseño**: Responsive (mobile-first)
- **Componentes**: Cards, grids, forms, buttons
- **Navegación**: Fixed header con contador de carrito
- **Mensajes**: Flash messages para feedback
- **Colores**: Esquema indigo/purple profesional

---

## 📱 Flujo de Usuario

1. **Explorar** → Usuario entra al sitio y ve productos destacados
2. **Navegar** → Explora categorías o busca productos
3. **Seleccionar** → Ve detalles del producto
4. **Agregar** → Añade productos al carrito
5. **Revisar** → Verifica el carrito
6. **Checkout** → Completa formulario con datos
7. **WhatsApp** → Envía pedido por WhatsApp
8. **Confirmar** → Vendedor confirma por WhatsApp

---

## 🚀 Todo está listo!

El sistema está **100% funcional** y listo para usar:

✅ Panel admin completo con Filament  
✅ Catálogo público con filtros y búsqueda  
✅ Carrito de compras con validación de stock  
✅ Integración WhatsApp para pedidos  
✅ Base de datos con datos de ejemplo  
✅ Diseño responsivo profesional  

**Solo necesitas:**
1. Hacer pull del repositorio
2. Levantar Docker
3. Compilar assets
4. ¡Empezar a vender!

