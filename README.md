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
node scripts/set-configs.js
```

Esto hará:

* `composer install`
* Copia `.env` y genera la key (`php artisan key:generate`)
* Ejecuta migraciones (`php artisan migrate`)
* Instala dependencias Node (`npm install`)

> Solo se necesita ejecutar **una vez**.

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
   (set-configs.js / npm run set:config)
       │
       ▼
Ejecutar script de desarrollo
   (start-simple.js / npm run dev:simple)
       │
       ▼
Proyecto levantado en http://localhost:8000
```

> Este flujo asegura que todo esté configurado correctamente, incluyendo dependencias y base de datos.

---

## 5. Archivos de scripts

* `scripts/set-configs.js` → prepara todo (Composer + Node + migraciones)
* `scripts/start-simple.js` → levanta Vite y Laravel en modo desarrollo, con filtrado de warnings

