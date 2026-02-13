import { spawnSync } from 'child_process';
import fs from 'fs';

console.log('==========================================');
console.log('       PREPARANDO EL PROYECTO            ');
console.log('==========================================\n');

// Composer install
console.log('Instalando dependencias de PHP (composer)...');
let composerInstall = spawnSync('composer', ['install'], { stdio: 'inherit' });
if (composerInstall.status !== 0) process.exit(1);

// Composer dump-autoload
console.log('Actualizando autoload de Composer...');
let dumpAutoload = spawnSync('composer', ['dump-autoload'], { stdio: 'inherit' });
if (dumpAutoload.status !== 0) process.exit(1);

// Crear archivo .env si no existe
if (!fs.existsSync('.env')) {
    console.log('Generando archivo .env...');
    fs.copyFileSync('.env.example', '.env');
} else {
    console.log('Archivo .env ya existe');
}

// Generar APP_KEY
console.log('Generando key de Laravel...');
let keyGenerate = spawnSync('php', ['artisan', 'key:generate'], { stdio: 'inherit' });
if (keyGenerate.status !== 0) process.exit(1);

// NPM install
console.log('Instalando dependencias de Node...');
let npmInstall = spawnSync('npm', ['install'], { stdio: 'inherit' });
if (npmInstall.status !== 0) process.exit(1);

console.log('\nProyecto preparado correctamente. Ahora puedes ejecutar start-simple.js para levantar el servidor.');
