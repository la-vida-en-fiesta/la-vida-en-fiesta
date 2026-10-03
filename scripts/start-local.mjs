import { spawn } from 'node:child_process';
import { mkdirSync, existsSync, readFileSync, writeFileSync } from 'node:fs';
import { resolve } from 'node:path';

const site = resolve('.local/wordpress');
mkdirSync(site, { recursive: true });
const blueprint = JSON.parse(readFileSync(resolve('local/blueprint.json'), 'utf8'));
// Keep subsequent starts independent of translation downloads already persisted.
if (existsSync(resolve(site, 'wp-content/languages/es_ES.mo')) &&
    existsSync(resolve(site, 'wp-content/languages/plugins/woocommerce-es_ES.mo'))) {
  blueprint.steps = blueprint.steps.filter((step) => step.step !== 'setSiteLanguage');
}
const runtimeBlueprint = resolve('.local/blueprint-runtime.json');
writeFileSync(runtimeBlueprint, JSON.stringify(blueprint, null, 2));
const args = [
  resolve('scripts/playground-loopback.mjs'), 'server',
  '--port=9400', '--site-url=http://127.0.0.1:9400', '--workers=2',
  '--define', 'WP_ENVIRONMENT_TYPE', 'local', '--define', 'FS_METHOD', 'direct',
  '--mount-dir-before-install', site, '/wordpress',
  '--mount-dir', resolve('theme/fiesta-viva'), '/wordpress/wp-content/themes/fiesta-viva',
  '--blueprint', runtimeBlueprint,
  '--wordpress-install-mode', existsSync(resolve(site, 'wp-load.php'))
    ? 'install-from-existing-files-if-needed' : 'download-and-install'
];
const child = spawn(process.execPath, args, { stdio: 'inherit', windowsHide: true });
child.on('error', (error) => { console.error(error.message); process.exitCode = 1; });
child.on('exit', (code) => { process.exitCode = code ?? 1; });
process.on('SIGINT', () => child.kill('SIGINT'));
process.on('SIGTERM', () => child.kill('SIGTERM'));
