# Comprobaciones de La Vida en Fiesta

El proyecto usa WordPress y WooCommerce con tema propio en `theme/fiesta-viva/`, arranque Node en `scripts/start-local.mjs` y blueprint en `local/blueprint.json`. Lee README y package.json actuales: las herramientas pueden cambiar.

No hay un comando `npm test` declarado actualmente. No inventes ese comando ni interpretes su ausencia como prueba pasada.

Selecciona comprobaciones segun el cambio y repitelas sobre el resultado integrado con la principal:

- Siempre: revisar diff completo del PR, `git diff --check`, cambios en configuracion, rutas de assets, referencias e incompatibilidades evidentes con el codigo remoto.
- JavaScript: `node --check <archivo>` para archivos modificados. Si afecta al arranque, prueba el entorno local segun README.
- PHP: `php -l <archivo>` si hay PHP nativo; si no, usa el runtime PHP WebAssembly que ya utiliza el proyecto. Si no se dispone de una comprobacion fiable para PHP modificado, indica la limitacion y no hagas merge automatico.
- `seasons.php`: `node scripts/verify-seasons.mjs` comprueba fechas, limites y recurrencia usando PHP WebAssembly. Necesita las dependencias del proyecto.
- Assets de campañas: `scripts/verify-season-assets.py` valida transparencia de imagenes concretas, con Pillow. No demuestra que toda la interfaz funcione.
- Catalogo y carrito: revisa en navegador numero/color, foto de variante, precio, cantidad, agregado sin salir de pagina, eliminacion, seguir comprando y aviso de envio cuando correspondan al diff. Ejecuta comprobaciones de variantes aplicables.
- `scripts/verify-balloon.py` depende de la SQLite local y del producto de referencia 82644. `scripts/verify-local-order.py` depende de un pedido de prueba existente. No generan datos ni son pruebas generales de una copia nueva; no uses una base antigua para declarar validado codigo nuevo.
- JSON/blueprint: parsea el JSON y verifica las rutas referidas; cambios en WooCommerce o persistencia requieren probar el comportamiento relevante sin borrar la base local.
- Skills y documentacion: valida manifiestos, enlaces y scripts modificados con escenarios aislados. No exige arrancar WordPress cuando el cambio solo afecta instrucciones.

No publiques bases de datos ni datos personales. No generes pagos o envios reales para comprobar este entorno. La compatibilidad demostrada en PHP WebAssembly/SQLite no certifica compatibilidad de produccion con PHP/MySQL ni con servicios de pagos.

Si instalas dependencias necesarias para comprobar el cambio, respeta el lockfile y no lo reescribas por comodidad. Distingue falta de herramientas, fallos anteriores y regresiones nuevas. Un fallo material impide el merge automatico aunque GitHub no tenga CI obligatorio.
