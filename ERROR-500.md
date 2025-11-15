# ⚠️ Solucionando Error 500

Si ves **HTTP ERROR 500**, sigue estos pasos:

---

## 🚀 Solución Rápida (Automática)

### Windows:
```bash
git pull origin claude/clothing-store-whatsapp-011NhnYeo3Qe6CxMXt9fZiqi
fix-errors.bat
```

### Linux/Mac:
```bash
git pull origin claude/clothing-store-whatsapp-011NhnYeo3Qe6CxMXt9fZiqi
./fix-errors.sh
```

Esto corregirá automáticamente:
- ✅ Genera APP_KEY
- ✅ Ejecuta migraciones
- ✅ Carga datos iniciales
- ✅ Configura permisos
- ✅ Limpia cache
- ✅ Compila assets

---

## 🔧 Solución Manual (Paso a Paso)

Si el script automático no funciona, ejecuta cada comando:

```bash
# 1. Pull del código
git pull origin claude/clothing-store-whatsapp-011NhnYeo3Qe6CxMXt9fZiqi

# 2. Levantar Docker
docker compose up -d --build

# 3. Generar APP_KEY
docker compose exec app php artisan key:generate --force

# 4. Ejecutar migraciones
docker compose exec app php artisan migrate --force

# 5. Cargar datos iniciales
docker compose exec app php artisan db:seed --force

# 6. Configurar permisos
docker compose exec app chmod -R 775 storage bootstrap/cache

# 7. Crear storage link
docker compose exec app php artisan storage:link

# 8. Limpiar cache
docker compose exec app php artisan config:clear
docker compose exec app php artisan cache:clear
docker compose exec app php artisan route:clear

# 9. Instalar npm
docker compose exec app npm install

# 10. Compilar assets
docker compose exec app npm run build
```

---

## 🔍 Diagnóstico

Para ver qué está pasando:

```bash
# Ver logs de la aplicación
docker compose logs -f app

# O ejecutar script de diagnóstico
./diagnose.sh   # Linux/Mac
```

---

## 📋 Causas comunes del Error 500

1. **APP_KEY no generada** → Ejecuta `php artisan key:generate`
2. **Migraciones no ejecutadas** → Ejecuta `php artisan migrate`
3. **Permisos incorrectos** → Ejecuta `chmod -R 775 storage`
4. **Cache corrupta** → Ejecuta `php artisan cache:clear`
5. **Base de datos no conectada** → Verifica docker compose ps

---

## ✅ Verificación

Después de corregir, deberías ver:

- ✅ Página principal con productos
- ✅ Sin errores 500
- ✅ Panel admin funcionando en /admin

---

## 💡 Solución Nuclear (Último Recurso)

Si nada funciona, reinicia todo desde cero:

```bash
# Borrar TODO y empezar de nuevo
docker compose down -v
docker compose up -d --build

# Esperar 30 segundos
sleep 30

# Ejecutar fix-errors
./fix-errors.sh   # Linux/Mac
fix-errors.bat    # Windows
```

---

## 📞 Necesitas más ayuda?

1. Ejecuta `./diagnose.sh` y comparte el resultado
2. Ejecuta `docker compose logs app` y busca el error en rojo
3. Verifica que todos los contenedores estén corriendo: `docker compose ps`
