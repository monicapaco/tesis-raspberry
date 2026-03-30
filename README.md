# Tesis Raspberry - Proyecto Laravel

## 1. Prerrequisitos

Antes de iniciar, asegúrate de tener instalado:

* **Node.js**: v22.18.0 recomendada
* **PHP**: v8.2.30 recomendada
* **Composer**: v2.9.4 recomendada
* **Servidor local con MySQL**:
  * Windows → se recomienda Laragon
  * Linux → cualquier instalación estándar de MySQL o MariaDB

> Las versiones exactas están en el archivo `.versions` para referencia.
> Windows: el proyecto puede estar en cualquier carpeta accesible desde la terminal, no es necesario `htdocs`.
> Linux: igual, cualquier carpeta de proyectos funciona.

---

## 2. Preparación del proyecto

### Usando scripts (recomendado)

1. Instala todas las dependencias de PHP y Node, genera la key y realiza migraciones:

```bash
npm run set:config
```

o

```bash
node scripts/setup.mjs
```

Esto hará:

* `composer install` y `dump-autoload`
* Copia `.env.example → .env` y genera la key (`php artisan key:generate`)
* Valida la configuración de base de datos antes de migrar
* Ejecuta migraciones (`php artisan migrate`)
* Instala dependencias Node (`npm install`)
* Opcionalmente ejecuta seeders

> Solo se necesita ejecutar **una vez**.
> Si las migraciones fallan, el script indicará la causa más probable y cómo resolverla.
> El log de cada ejecución se guarda en `storage/logs/setup.log`.

### Manualmente (opcional)

Si prefieres hacerlo sin scripts:

```bash
# Instalar dependencias PHP
composer install

# Copiar el archivo de entorno y generar clave
cp .env.example .env
php artisan key:generate

# Migrar la base de datos
php artisan migrate

# Instalar dependencias Node
npm install
```

---

## 3. Levantar el proyecto (modo desarrollo)

### Usando scripts (recomendado)

```bash
npm run dev:simple
```

o

```bash
node scripts/start-simple.js
```

Esto hará:

* Inicia Vite para el frontend (`npm run dev`)
* Levanta servidor Laravel (`php artisan serve`)
* Filtra automáticamente los warnings de Sass
* Muestra la URL de la app: `http://localhost:8000`

### Manualmente (opcional)

```bash
# Levantar Vite
npm run dev

# Levantar Laravel
php artisan serve
```

---

## 4. Flujo resumido

```text
PRERREQUISITOS INSTALADOS
       │
       ▼
Ejecutar script de preparación
   npm run set:config
       │
       ▼
Ejecutar script de desarrollo
   npm run dev:simple
       │
       ▼
Proyecto levantado en http://localhost:8000
```

> Este flujo asegura que todo esté configurado correctamente, incluyendo dependencias y base de datos.

---

## 5. Deploy a producción (Raspberry Pi)

El proyecto incluye un script de deploy para actualizar el servidor de producción. Solo aplica si tienes acceso SSH a la Raspberry Pi.

### Requisitos

* Acceso SSH a la Raspberry Pi
* El proyecto clonado en `/var/www/app`
* Permisos de ejecución en el script (solo configurar una vez):

```bash
chmod +x ./scripts/deploy-prod.sh
```

### Uso

```bash
./scripts/deploy-prod.sh <rama>
```

**Ejemplo** — deploy desde la rama `main`:

```bash
./scripts/deploy-prod.sh main
```

### ¿Qué hace el script?

1. Verifica que la rama exista en el repositorio remoto
2. Detecta si hay cambios nuevos (si no hay, termina sin hacer nada)
3. Muestra los commits que se van a aplicar
4. **Hace backup de la base de datos** antes de tocar nada
5. Activa el modo mantenimiento (los usuarios ven un aviso en vez de errores)
6. Descarga los cambios (`git pull`)
7. Actualiza dependencias PHP y JS
8. Ejecuta migraciones
9. Regenera el caché de Laravel
10. Ajusta permisos de carpetas
11. Desactiva el modo mantenimiento

> Si algo falla en cualquier paso, el sitio **siempre** vuelve a estar disponible automáticamente.
> El log de cada deploy se guarda en `/var/log/deploy.log`.

### ¿Cuándo NO usar el script?

* Si el `.env` de producción no está configurado
* Si es la primera vez que se instala el proyecto en el servidor (hacer setup manual)
* Si la migración que se va a aplicar borra columnas o tablas — revisar antes con el equipo

---

## 6. Archivos de scripts

| Archivo | Descripción |
|---|---|
| `scripts/setup.mjs` | Prepara el proyecto: dependencias, `.env`, migraciones |
| `scripts/start-simple.js` | Levanta Vite y Laravel en modo desarrollo |
| `scripts/deploy-prod.sh` | Deploy a producción en la Raspberry Pi |
