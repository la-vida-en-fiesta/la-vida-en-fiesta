# La Vida en Fiesta

Desarrollo local de una tienda WordPress + WooCommerce, con el tema propio Fiesta Viva.

## Iniciar en Windows

Requiere Node.js 20.18 o superior. Desde esta carpeta:

```powershell
npm.cmd install
npm.cmd run dev
```

Abrir http://127.0.0.1:9400/. Para detener el servidor, pulsar Ctrl+C en su terminal.

La primera portada ya incluye escaparates ilustrados de cumpleaños y disfraces, el directorio de celebraciones, regalos y complementos, búsqueda de productos y enlaces a Instagram. Las ilustraciones son decorativas y no representan productos del catálogo.

La primera ejecución descarga WordPress, la integración SQLite y WooCommerce; requiere conexión a Internet. La carpeta `.local/wordpress/` conserva la instalación, los productos y la base de datos entre ejecuciones. No borrarla para reiniciar el servidor. Respaldarla antes de cambios importantes. Está excluida de Git, como `node_modules`.

## Qué se desarrolla

- Tema editable: `theme/fiesta-viva/`.
- Configuración reproducible: `local/blueprint.json`.
- Identidad aprobada: `design/fiesta-viva.md`.
- Contexto del negocio: `PRODUCT.md`.

El catálogo comienza vacío, con las categorías de la tienda. No hay precios, productos ni stock inventados. Hayden, pagos y envíos siguen pendientes de integración. El entorno local usa SQLite y PHP WebAssembly: antes de publicar hay que validar WordPress y WooCommerce con PHP y MySQL/MariaDB reales, además de probar pagos, notificaciones y sincronización de stock.

## GitHub

Repositorio creado: https://github.com/la-vida-en-fiesta/la-vida-en-fiesta

Los archivos locales aún no se han subido. No subir `.local`, bases de datos, archivos de configuración privada ni credenciales.
