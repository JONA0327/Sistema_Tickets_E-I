# Configuración para Producción - Sistema de Ayuda

## Configuración de Directorios para Imágenes

### Método 1: Comando Artisan (Recomendado)
```bash
php artisan help:setup-images
```

### Método 2: Comandos Manuales

#### En Linux/Unix:
```bash
# Crear directorio
mkdir -p storage/app/public/help-images

# Establecer permisos
chmod 755 storage/app/public/help-images

# Crear enlace simbólico
php artisan storage:link
```

#### En Windows:
```cmd
# Crear directorio
mkdir storage\app\public\help-images

# Crear enlace simbólico
php artisan storage:link
```

### Método 3: Verificación Manual

1. **Verificar que existe el directorio:**
   ```
   storage/app/public/help-images/
   ```

2. **Verificar que existe el enlace simbólico:**
   ```
   public/storage -> ../storage/app/public
   ```

3. **Verificar permisos (Linux/Unix):**
   ```bash
   ls -la storage/app/public/help-images/
   ```

## Configuración del Servidor Web

### Apache (.htaccess)
```apache
# Permitir acceso a archivos de imagen
<Directory "/path/to/project/public/storage/help-images">
    Options -Indexes
    AllowOverride None
    Require all granted
</Directory>
```

### Nginx
```nginx
location ~* \.(jpg|jpeg|png|gif)$ {
    expires 1M;
    access_log off;
    add_header Cache-Control "public, immutable";
}
```

## Variables de Entorno

Asegurar que en `.env` esté configurado:
```env
APP_URL=https://tu-dominio.com
FILESYSTEM_DISK=local
```

## Verificación Post-Despliegue

1. **Probar subida de imagen:**
   - Ir al panel de administración
   - Crear/editar una sección de ayuda
   - Subir una imagen de prueba

2. **Verificar URL de imagen:**
   - Formato esperado: `https://tu-dominio.com/storage/help-images/nombre-archivo.gif`

3. **Verificar en el manual público:**
   - Las referencias `[img:figura1]` deben mostrarse como imágenes

## Solución de Problemas

### Error: "No such file or directory"
```bash
php artisan help:setup-images
php artisan storage:link
```

### Error: "Permission denied"
```bash
sudo chown -R www-data:www-data storage/
sudo chmod -R 755 storage/
```

### Imágenes no se muestran
1. Verificar APP_URL en .env
2. Verificar enlace simbólico: `ls -la public/storage`
3. Verificar permisos del directorio

## Notas Importantes

- Las imágenes se guardan físicamente, no en base64
- Soporte para: JPEG, PNG, GIF (hasta 100MB)
- Las referencias se procesan automáticamente en el contenido público
- El sistema crea automáticamente el directorio si no existe