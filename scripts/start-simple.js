import { spawn } from 'child_process';

console.log('==========================================');
console.log('       SERVIDOR DE DESARROLLO            ');
console.log('==========================================\n');

console.log('App: http://localhost:8000');
console.log('Nota: El proyecto usa Bootstrap con sintaxis Sass antigua');
console.log('(Se filtran los warnings de deprecación)\n');
console.log('==========================================\n');

// ---------------------------
// Ejecutar Vite
// ---------------------------
const vite = spawn('npm', ['run', 'dev'], {
  stdio: ['ignore', 'pipe', 'pipe'],
  shell: true
});

let hasShownSassWarning = false;

vite.stderr.on('data', (data) => {
  const lines = data.toString().split('\n');

  for (let line of lines) {
    line = line.trim();
    if (!line) continue;

    // Filtrar warnings de Sass
    if (line.includes('DEPRECATION WARNING') ||
        line.includes('is deprecated') ||
        line.match(/[╷╵│┌──>]/) ||
        line.match(/\.scss\s+\d+:\d+/)) {

      if (!hasShownSassWarning) {
        console.log('[Filtrando warnings de Sass...]');
        hasShownSassWarning = true;
      }
      continue;
    }

    // Mostrar errores reales
    if (line.toLowerCase().includes('error')) {
      console.log(`[ERROR Vite] ${line}`);
    } else if (line) {
      console.log(`[Vite] ${line}`);
    }
  }
});

vite.stdout.on('data', (data) => {
  const output = data.toString();
  if (output.includes('ready in')) {
    console.log('[Vite iniciado correctamente]\n');
  }
});

// ---------------------------
// Ejecutar PHP Artisan serve
// ---------------------------
const phpServe = spawn('php', ['artisan', 'serve'], {
  stdio: 'inherit',
  shell: true
});
