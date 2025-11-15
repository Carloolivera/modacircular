# 🚀 Setup Rápido - ModaCircular

## ⚡ Pasos para ver el sitio funcionando

### 1. Hacer pull del código
```bash
git pull origin claude/clothing-store-whatsapp-011NhnYeo3Qe6CxMXt9fZiqi
```

### 2. Levantar Docker (si no está levantado)
```bash
docker compose up -d --build
```

Esto instalará automáticamente:
- ✅ Dependencias de Composer
- ✅ Creará la base de datos
- ✅ Ejecutará migraciones
- ✅ Cargará datos de ejemplo

### 3. Instalar dependencias de Node.js
```bash
docker compose exec app npm install
```

### 4. Compilar assets (CSS y JS)
```bash
docker compose exec app npm run build
```

### 5. Abrir en el navegador
- **Sitio público**: http://localhost:8000
- **Panel admin**: http://localhost:8000/admin
  - Usuario: `admin@modacircular.com`
  - Contraseña: `admin123`

---

## 🔧 Si algo no funciona

### Limpiar cache de Laravel
```bash
docker compose exec app php artisan cache:clear
docker compose exec app php artisan config:clear
docker compose exec app php artisan view:clear
docker compose exec app php artisan route:clear
```

### Recompilar assets en modo desarrollo (con watch)
```bash
docker compose exec app npm run dev
```

### Ver logs
```bash
docker compose logs -f app
```

### Reiniciar todo desde cero
```bash
docker compose down -v
docker compose up -d --build
docker compose exec app npm install
docker compose exec app npm run build
```

---

## ✅ Verificación

Después del paso 4, deberías ver:
- ✅ Página principal con hero section y productos destacados
- ✅ Navegación funcional
- ✅ Diseño con Tailwind CSS (colores, espaciado, etc.)
- ✅ Carrito en la navegación

Si ves la página default de Laravel, significa que los assets no se compilaron correctamente. Ejecuta nuevamente:
```bash
docker compose exec app npm run build
```

---

## 📱 Primera vez usando el sitio

1. Ve a http://localhost:8000/admin
2. Inicia sesión con `admin@modacircular.com` / `admin123`
3. Crea algunas categorías y productos
4. Sube imágenes a los productos
5. Marca algunos productos como "destacados"
6. Ve al sitio público en http://localhost:8000
7. ¡Explora el catálogo y prueba el carrito!

---

## 💡 Tips

- Los productos se ocultan automáticamente cuando stock = 0
- Puedes filtrar productos por categoría
- El carrito se guarda en la sesión del navegador
- Al hacer checkout, el pedido se envía por WhatsApp
- Configura el número de WhatsApp en "Configuraciones" del admin
