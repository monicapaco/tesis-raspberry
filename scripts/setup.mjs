import { spawnSync }  from 'child_process';
import fs             from 'fs';
import path           from 'path';
import readline       from 'readline';

// ─────────────────────────────────────────────
//  Utilidades
// ─────────────────────────────────────────────

const LOG_FILE = 'storage/logs/setup.log';
const isWindows = process.platform === 'win32';

function timestamp() {
  return new Date().toISOString().replace('T', ' ').split('.')[0];
}

function log(msg) {
  const line = `[${timestamp()}] ${msg}`;
  fs.appendFileSync(LOG_FILE, line + '\n');
}

function print(emoji, msg) {
  console.log(`${emoji}  ${msg}`);
  log(msg);
}

function printSection(title) {
  console.log('');
  console.log(`─────────────────────────────────────────`);
  console.log(`  ${title}`);
  console.log(`─────────────────────────────────────────`);
  log(`=== ${title} ===`);
}

function fail(msg) {
  console.error(`\n❌  ERROR: ${msg}`);
  console.error(`    Revisa el archivo setup.log para más detalles.\n`);
  log(`ERROR: ${msg}`);
  process.exit(1);
}

// Ejecuta un comando y termina si falla
function run(cmd, args = [], options = {}) {
  const realCmd = isWindows && !cmd.endsWith('.exe') ? `${cmd}.cmd` : cmd;
  print('⚙️ ', `Ejecutando: ${cmd} ${args.join(' ')}`);

  const result = spawnSync(realCmd, args, {
    stdio  : 'inherit',
    shell  : isWindows,   // en Windows necesita shell para encontrar binarios npm/composer
    ...options,
  });

  if (result.error) {
    // Reintenta sin .cmd por si acaso
    if (isWindows) {
      const retry = spawnSync(cmd, args, { stdio: 'inherit', shell: true, ...options });
      if (retry.status !== 0) fail(`Falló: ${cmd} ${args.join(' ')}`);
      return;
    }
    fail(`No se encontró el comando "${cmd}". ¿Está instalado?`);
  }

  if (result.status !== 0) {
    fail(`Falló: ${cmd} ${args.join(' ')}`);
  }
}

// Pregunta al usuario en la terminal
function ask(question) {
  const rl = readline.createInterface({ input: process.stdin, output: process.stdout });
  return new Promise(resolve => rl.question(question, ans => { rl.close(); resolve(ans.trim()); }));
}

// Lee un valor del .env
function getEnvValue(key) {
  if (!fs.existsSync('.env')) return null;
  const match = fs.readFileSync('.env', 'utf8').match(new RegExp(`^${key}=(.*)$`, 'm'));
  return match ? match[1].trim() : null;
}

// ─────────────────────────────────────────────
//  INICIO
// ─────────────────────────────────────────────

console.log('');
console.log('==========================================');
console.log('       PREPARANDO EL PROYECTO 🚀         ');
console.log('==========================================');
log('--- Inicio de setup ---');

// ─────────────────────────────────────────────
//  1. Requisitos previos
// ─────────────────────────────────────────────
printSection('1. Verificando herramientas instaladas');

for (const [cmd, args, friendly] of [
  ['php',      ['-v'],        'PHP'],
  ['composer', ['--version'], 'Composer'],
  ['node',     ['--version'], 'Node.js'],
  ['npm',      ['--version'], 'npm'],
]) {
  const r = spawnSync(cmd, args, { shell: isWindows });
  if (r.error || r.status !== 0) {
    fail(`"${friendly}" no está instalado o no está en el PATH.\n    Instálalo y vuelve a ejecutar este script.`);
  }
  print('✅', `${friendly} encontrado`);
}

// ─────────────────────────────────────────────
//  2. Archivo .env
// ─────────────────────────────────────────────
printSection('2. Archivo .env');

if (!fs.existsSync('.env.example')) {
  fail('No se encontró .env.example. ¿Estás en la carpeta raíz del proyecto?');
}

if (!fs.existsSync('.env')) {
  print('📄', 'Copiando .env.example → .env');
  fs.copyFileSync('.env.example', '.env');
  print('✅', '.env creado');
} else {
  print('✅', '.env ya existe, no se sobreescribe');
}

// ─────────────────────────────────────────────
//  3. Composer
// ─────────────────────────────────────────────
printSection('3. Dependencias PHP (Composer)');

run('composer', ['install', '--no-interaction']);
run('composer', ['dump-autoload']);

// ─────────────────────────────────────────────
//  4. App key
// ─────────────────────────────────────────────
printSection('4. Laravel App Key');

const currentKey = getEnvValue('APP_KEY');
if (!currentKey) {
  run('php', ['artisan', 'key:generate']);
} else {
  print('✅', 'APP_KEY ya existe, no se regenera');
}

// ─────────────────────────────────────────────
//  5. npm install
// ─────────────────────────────────────────────
printSection('5. Dependencias JS (npm)');

if (fs.existsSync('package.json')) {
  run('npm', ['install']);
} else {
  print('ℹ️ ', 'No hay package.json, se omite npm install');
}

// ─────────────────────────────────────────────
//  6. Base de datos → validar ANTES de migrar
// ─────────────────────────────────────────────
printSection('6. Base de datos y migraciones');

const dbConnection = getEnvValue('DB_CONNECTION') || 'mysql';
const dbName       = getEnvValue('DB_DATABASE');
const dbUser       = getEnvValue('DB_USERNAME');
const dbPass       = getEnvValue('DB_PASSWORD') || '';
const dbHost       = getEnvValue('DB_HOST')     || '127.0.0.1';
const dbPort       = getEnvValue('DB_PORT')     || '3306';

if (dbConnection === 'sqlite') {
  // SQLite: solo verifica que exista el archivo o lo crea
  const sqlitePath = dbName && dbName !== ':memory:' ? dbName : 'database/database.sqlite';
  if (!fs.existsSync(sqlitePath)) {
    print('📄', `Creando archivo SQLite: ${sqlitePath}`);
    fs.mkdirSync(path.dirname(sqlitePath), { recursive: true });
    fs.writeFileSync(sqlitePath, '');
  }
  print('✅', 'Base de datos SQLite lista');

} else {
  // MySQL / PostgreSQL: verificar que los campos no estén vacíos
  const missing = [];
  if (!dbName) missing.push('DB_DATABASE');
  if (!dbUser) missing.push('DB_USERNAME');

  if (missing.length > 0) {
    console.log('');
    print('⚠️ ', `Faltan estos valores en tu .env: ${missing.join(', ')}`);
    print('⚠️ ', 'Las migraciones pueden fallar si la DB no existe o las credenciales son incorrectas.');
    const answer = await ask('\n   ¿Deseas continuar de todas formas? (s/n): ');
    if (answer.toLowerCase() !== 's') {
      console.log('\n   Edita tu .env y vuelve a ejecutar el script.\n');
      log('Setup cancelado por el usuario (credenciales faltantes)');
      process.exit(0);
    }
  } else {
    print('✅', `DB configurada → ${dbConnection}://${dbHost}:${dbPort}/${dbName}`);
  }
}

// Intentar migrar con mensaje claro si falla
print('🗄️ ', 'Ejecutando migraciones...');

const migrate = spawnSync(
  isWindows ? 'php' : 'php',
  ['artisan', 'migrate', '--no-interaction'],
  { stdio: 'inherit', shell: isWindows }
);

if (migrate.status !== 0) {
  console.error('');
  console.error('❌  Las migraciones fallaron. Causas más comunes:');
  console.error('    1. La base de datos no existe → créala manualmente');
  console.error(`       MySQL:  CREATE DATABASE ${dbName || 'tu_db'};`);
  console.error('    2. Credenciales incorrectas en .env (DB_USERNAME / DB_PASSWORD)');
  console.error('    3. El servidor de base de datos no está corriendo');
  console.error('');
  console.error('    Revisa setup.log y corrige tu .env, luego vuelve a ejecutar.');
  log('ERROR: migraciones fallaron');
  process.exit(1);
}

print('✅', 'Migraciones aplicadas correctamente');

// ─────────────────────────────────────────────
//  7. Seeders (opcional)
// ─────────────────────────────────────────────
printSection('7. Seeders (datos iniciales)');

const seedAnswer = await ask(
  '   ¿Deseas ejecutar los seeders?\n' +
  '   ⚠️  Si ya tienes datos podrían duplicarse o borrarse según cómo estén escritos los seeders.\n' +
  '   Solo responde "s" si es una instalación nueva. (s/n): '
);
if (seedAnswer.toLowerCase() === 's') {
  run('php', ['artisan', 'db:seed', '--no-interaction']);
  print('✅', 'Seeders ejecutados');
} else {
  print('ℹ️ ', 'Seeders omitidos');
}

// ─────────────────────────────────────────────
//  Fin
// ─────────────────────────────────────────────
console.log('');
console.log('==========================================');
console.log('  ✅  ¡Proyecto listo!                   ');
console.log('  👉  Ejecuta: npm run dev:simple        ');
console.log('==========================================');
console.log('');
log('--- Setup completado correctamente ---');
