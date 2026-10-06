# Ficha de globo número de 32 pulgadas

Fecha de evidencia: 2026-10-02. Modo de esta superficie: **Operate**.

## Alcance y tarea

Extensión de la ficha WooCommerce existente del producto de referencia `82644`. La persona elige un número y un color y puede leer o limpiar su selección en la vista previa local. Esta intervención conserva Fiesta Viva; no establece un nuevo sistema visual ni reemplaza `DESIGN.md` o `.impeccable/design.json`.

## Contenido y comportamiento

- Número: selector nativo con opciones del 0 al 9, incluyendo el 0 como selección válida.
- Color: selector nativo con Dorado, Plateado, Azul, Verde, Rojo, Negro, Fucsia, Multicolor y Rosa oro.
- Datos: 90 combinaciones únicas almacenadas como variaciones reales de WooCommerce; todas tienen precio regular UYU 130.
- Estado inicial o incompleto: «Elegí las dos opciones para ver tu selección.» Al completar ambas opciones se informa número, color y $130 en una región `aria-live="polite"`, atómica.
- «Limpiar selección» restablece los dos selectores y el mensaje inicial. Se deshabilita cuando ambos campos están vacíos. El formulario impide el envío.
- La fotografía del número 3 dorado permanece intacta al cambiar la selección. Un texto visible explica que es una imagen de referencia, sin simular fotografías de otras variantes.
- La compra no está habilitada en esta vista previa local. El aviso «Disponibilidad por confirmar» no acredita stock físico ni sincronización con Hayden.

## Relación con el sistema incumbente

Se compararon `DESIGN.md`, `.impeccable/design.json`, el CSS del tema y las capturas de la ficha. La superficie conserva fondo crema, texto tinta, acciones y precio frambuesa, títulos Fredoka y texto Nunito Sans. Mantiene la cabecera con búsqueda, el logo y el acceso a catálogo, además de la estructura WooCommerce de imagen, resumen, información adicional y productos relacionados.

Los nuevos controles reutilizan el borde fino y radio de 8px de campos de comercio, el foco visible global del tema y superficies blancas. No incorporan sombras de interfaz. La elección se presenta en dos columnas con separación de 16px, apiladas bajo 450px; los selectores tienen un mínimo de 48px de alto y el reset un mínimo de 44px. La extensión de ficha añade imagen sobre blanco con radio de 14px y jerarquía propia de resumen; son decisiones locales ya implementadas, no nuevos tokens normativos del sistema completo.

La comparación no mostró una sustitución de identidad visual. La ficha usa fotografía real de referencia en lugar de ilustración festiva decorativa porque el contenido del producto lo requiere. La foto permanece explícitamente diferenciada de la variante elegida.

## Implementación y evidencia

Código observado:

- `theme/fiesta-viva/local-catalog.php`: importación exclusiva del entorno local, atributos y variaciones WooCommerce; filtro de compra para productos de vista previa.
- `theme/fiesta-viva/product-options.php`: formulario de selección exclusivo de esta referencia en entorno local, etiquetas, avisos y conservación de la ficha WooCommerce.
- `theme/fiesta-viva/assets/product-options.js`: actualización del estado, aceptación del número 0, reset y bloqueo de envío.
- `theme/fiesta-viva/style.css`: estilos de ficha, campos y adaptación móvil.

Pruebas de navegador comunicadas por la revisión de implementación: selección 0 / Verde, limpieza de selección y selección 9 / Rosa oro. El mensaje responde a ambas combinaciones y la imagen sigue siendo el 3 dorado. Las capturas revisadas muestran 9 / Rosa oro en escritorio y móvil:

- `.impeccable/review/globo-desktop.png` (1265 × 2049).
- `.impeccable/review/globo-mobile.png` (375 × 2623).

Verificación de base de datos comunicada por la revisión de implementación: `scripts/verify-balloon.py` comprueba 90 variaciones, 90 pares únicos, números 0–9, los nueve colores y precio regular 130 en todas las variaciones. El script lee la base SQLite local en modo de solo lectura. Esta evidencia verifica el catálogo local; no confirma disponibilidad real.

El detector Impeccable no pudo ejecutarse por ausencia de su motor. No se realizó una auditoría formal de accesibilidad. Etiquetas nativas, foco global, región viva y tamaños de control son evidencia de implementación, no una declaración de conformidad WCAG.

## Desfase de contexto observado

`PRODUCT.md` mantiene declaraciones anteriores de que no se proporcionaron fichas, fotos ni precios reales, y de que no hay archivo de logo ni fotos de producto para la web. La ficha actual, su fotografía, precio y el logo registrado posteriormente aportan evidencia que vuelve esas frases incompletas. Se informa el desfase sin modificar contexto duradero fuera del alcance solicitado. La disponibilidad, pagos, logística e integración con Hayden continúan pendientes.
