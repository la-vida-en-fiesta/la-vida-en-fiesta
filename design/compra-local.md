# Carrito y compra simulada local

Fecha de evidencia: 2026-10-02. Modo de estas superficies: **Operate**.

## Alcance y evolución

La solicitud de probar carrito y compra completa amplía la vista previa anterior con un recorrido WooCommerce local: producto, carrito, checkout y pedido recibido. El nuevo modo permite añadir productos y generar pedidos simulados. Las frases de «compra no habilitada» en `design/ficha-globo.md` y `design/ficha-disfraces.md` describen el estado histórico previo; este brief registra el modo posterior sin alterar esos archivos ni reparar contexto duradero fuera de alcance.

Se conserva Fiesta Viva y la estructura nativa de WooCommerce. No se reemplazan `DESIGN.md` ni `.impeccable/design.json`, y este recorrido no habilita una operación comercial real.

## Funcionamiento y límites del modo

`fiesta_local_checkout_enabled()` exige entorno WordPress `local` y opción `fiesta_local_checkout_enabled=yes`. Una inicialización local ejecutada una sola vez configura las páginas existentes con shortcodes clásicos `[woocommerce_cart]` y `[woocommerce_checkout]`, permite checkout de invitado y activa el modo. Se utilizan formularios nativos para cantidades, variaciones, campos obligatorios y revisión del pedido.

La cabecera incorpora «Carrito (n)» y fragmentos de WooCommerce para actualizar la cantidad tras cambios. En el globo se recupera el formulario nativo de variaciones cuando el modo está activo; la imagen del 3 dorado permanece como referencia declarada. Los productos simples, incluido ninja, se pueden añadir en este modo aunque sus datos de talle o paquete sigan pendientes.

El banner anuncia «Modo de prueba local · Sin cobros ni correos. Los pedidos y el envío son simulados». El checkout pide datos ficticios, ofrece únicamente el gateway `fiesta_test` y usa el botón «Crear pedido de prueba». La pasarela no solicita tarjeta ni llama a un procesador real. Al crear el pedido guarda `_fiesta_local_test=yes`, establece estado `on-hold`, vacía el carrito y redirige a la confirmación «Pedido de prueba recibido. No se realizó ningún cobro».

Durante este modo, `pre_wp_mail` bloquea el envío de correo de WordPress y el filtro de reducción de stock devuelve `false`. No se introduce conexión con Hayden. Estos controles describen la implementación local; no prueban una integración de pagos, facturación o inventario de producción.

Existe un filtro que sustituiría tarifas calculadas por «Envío simulado (solo prueba)», de costo cero. En la configuración local ejercitada, WooCommerce tenía envío deshabilitado y no apareció ninguna fila de envío. Por ello no se ejercitó una tarifa ni el recorrido real de logística; el filtro permanece disponible para una configuración futura de prueba. No representa una decisión de costo de envío comercial.

## Evidencia del recorrido

Pruebas comunicadas por la revisión de implementación:

- Globo: número 0, color Verde, cantidad 2, total UYU 260. Se ejercitaron las validaciones de campos faltantes del checkout y luego datos ficticios de Uruguay.
- El pedido 127 llegó a confirmación, con carrito vacío y gateway de prueba. `scripts/verify-local-order.py`, lectura SQLite en modo de solo lectura, pasó la verificación de pedido `wc-on-hold`, total 260, marcador local, variante 0 / Verde, cantidad 2 y ausencia de identificador de transacción.
- Ninja: producto simple de UYU 1635 añadido al carrito y actualizado a cantidad 2, total UYU 3270. Se verificó el contador de cabecera en 2 mediante fragmentos. Esa prueba funcional antecede a las capturas finales, que muestran una unidad; posteriormente se confirmó eliminación del último artículo y contador 0. La pestaña final quedó en la confirmación del pedido 127.

Las capturas inspeccionadas tienen casos diferentes y no deben interpretarse como un único pedido:

| Capturas en `.impeccable/review/` | Evidencia |
| --- | --- |
| `carrito-desktop.png`, `carrito-mobile.png` | Ninja, cantidad 1, total $1.635 y contador 1. |
| `checkout-desktop.png`, `checkout-mobile.png` | Checkout con datos ficticios y ninja, cantidad 1, total $1.635, sin overlays de recálculo visibles. |
| `pedido-prueba-desktop.png`, `pedido-prueba-mobile.png` | Pedido 127 de dos globos 0 / Verde, total $260 y contador 0. |

Las seis capturas fueron reemplazadas tras la revisión final e inspeccionadas nuevamente. Las capturas de checkout anteriores con overlays y spinners fueron sustituidas por el formulario asentado: la revisión de implementación esperó a que `blockOverlay` estuviera oculto y confirmó cero overlays computados como visibles antes de capturar. La confirmación de pedido y la verificación de base de datos sustentan el resultado de la compra simulada del globo.

## Relación con el sistema incumbente

Se revisaron `local-checkout.php`, `functions.php`, `header.php`, `product-options.php`, `local-catalog.php`, el final de `style.css` y las seis capturas. El fondo crema, títulos Fredoka, cuerpo Nunito Sans y enlaces frambuesa conservan la identidad. El banner y el área de pago reutilizan menta; las tablas y campos usan blanco, borde fino y radios de comercio. La cabecera mantiene búsqueda visible en una segunda fila móvil junto al acceso a carrito.

La composición de carrito, checkout y confirmación sigue la estructura y estados de WooCommerce. La revisión final unificó los botones alternativos del plugin y «Crear pedido de prueba» en frambuesa con hover `#901646`, amplió el campo de cupón a 180px en escritorio y 48% en móvil, y dispuso el resumen del pedido en dos columnas en móvil. Las capturas finales muestran esas correcciones; el CTA «Finalizar compra» ya conserva la acción frambuesa del tema. Esta extensión adapta la legibilidad y el ancho del contenido sin afirmar una normalización completa de todos los estados del plugin ni introducir un sistema visual nuevo.

El detector Impeccable sigue sin motor disponible. No se realizó una auditoría formal WCAG, ni se validó un checkout de producción. La evidencia está acotada al recorrido local, los estados ejercitados y las capturas indicadas.

## Contexto pendiente

`PRODUCT.md` conserva declaraciones anteriores sobre ausencia de fichas, fotografías y precios, además del estado previo de capacidades. Se informa ese desfase sin editarlo. Disponibilidad física, contenido confirmado de disfraces, pagos reales, logística, facturación, sincronización con Hayden y alojamiento siguen sin resolverse por esta prueba.
