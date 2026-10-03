import { Server } from 'node:net';
// Playground does not expose a listen-host flag. Keep all numeric-port listeners
// in this process private to the computer, including its availability probe.
const originalListen = Server.prototype.listen;
Server.prototype.listen = function (...args) {
  if (typeof args[0] === 'number' && typeof args[1] !== 'string') {
    args.splice(1, 0, '127.0.0.1');
  }
  return originalListen.apply(this, args);
};
await import('../node_modules/@wp-playground/cli/wp-playground.js');
