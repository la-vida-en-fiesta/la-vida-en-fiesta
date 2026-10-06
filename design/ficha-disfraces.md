# Ficha reutilizable de disfraces

Fecha de evidencia: 2026-10-02. Modo de esta superficie: **Operate**.

## Alcance

Extensión de la ficha WooCommerce existente para mostrar talle, medidas y contenido del paquete con datos explícitos. Caso actual: `82643`, Disfraz de ninja, UYU 1635. Habitualmente hay un talle real por traje; la estructura admite varios talles confirmados para futuros productos, por ejemplo capas. Se conserva Fiesta Viva sin modificar `DESIGN.md`, `.impeccable/design.json` ni `PRODUCT.md`.

## Datos y estados

La fuente editable es `catalogo/disfraces/fichas.json`. El tema consume su copia en `theme/fiesta-viva/assets/catalogo/disfraces.json`; ambas coinciden en la evidencia revisada. La sincronización entre fuente y assets es manual: al editar datos confirmados se debe actualizar la copia. No hay sincronización automática con stock físico ni Hayden.

| Campo | Uso y significado |
| --- | --- |
| `codigo_archivo`, `nombre`, `descripcion_corta` | Identifican la ficha y su texto descriptivo. |
| `precio`, `moneda` | Precio del catálogo; ninja: 1635 UYU. |
| `modalidad_talle` | Registra `un_talle_por_traje` para este caso. |
| `talles_disponibles` | Lista de talles. Por instrucción del usuario, un campo omitido, `null` o una lista vacía toma «Talle único» como valor predeterminado. Un talle se presenta como texto; varios habilitan un selector nativo. Esto no confirma stock ni medidas. |
| `medidas_por_talle` | Medidas explícitas agrupadas por talle; `null` muestra medidas pendientes. |
| `piezas_incluidas` | Lista confirmada de piezas; `null` muestra contenido pendiente. Una lista vacía expresa «Sin accesorios adicionales», por lo que no debe usarse para representar desconocimiento. |
| `accesorios_no_incluidos` | Exclusiones expresas; `null` no acredita ninguna exclusión. |
| `referencia_observada` | Procedencia de la imagen original, etiqueta y modelo de empaque; es observación de referencia. |
| `estado_confirmacion` | Ninja: `pendiente_del_usuario`. |

El ninja muestra «Talle único» según la regla de carga establecida por el usuario. Medidas, piezas y exclusiones siguen en `null`: «Medidas del traje pendientes de confirmar» y «Contenido del paquete por confirmar». La etiqueta L observada en `82643.jpg` y el modelo de empaque 8060 no confirman disponibilidad ni piezas incluidas. No se deducen talles S/M/L, stock o accesorios a partir de la imagen.

## Comportamiento reutilizable

Sin talles especificados (campo omitido, `null` o lista vacía), la ficha renderiza «Talle único», regla expresamente solicitada por el usuario. Con un único talle específico, renderiza «Talle del traje» seguido del valor como texto. Con más de un talle, presenta «Elegí tu talle» y un selector nativo, cuya región `aria-live="polite"`, atómica, anuncia la selección y mantiene la disponibilidad por confirmar. El formulario impide su envío. Las ramas con talles específicos aún no se ejercitaron en el navegador con datos confirmados.

La imagen mostrada es una recreación de referencia. Cuando `piezas_incluidas` está pendiente, el aviso añade que los accesorios incluidos se detallarán al confirmar el paquete. Con una lista confirmada, conserva solo la declaración de imagen de referencia; evita anunciar como pendiente un contenido ya detallado. La extensión opera solo en el entorno local; la vista previa actual no permite comprar. Elegir un talle futuro no constituye reserva ni confirmación de stock.

## Comparación con el sistema incumbente

La revisión comparó el contexto visual ya leído, el CSS y las capturas actuales. La ficha conserva fondo crema, texto tinta, precio frambuesa, títulos Fredoka y texto Nunito Sans. Mantiene cabecera, búsqueda, catálogo y composición WooCommerce de imagen y resumen, seguida de valoraciones y productos relacionados. En móvil la imagen y el resumen se apilan.

Las secciones de detalles usan separador fino, relleno superior de 24px y títulos de 1.55rem. Las notas mantienen tinta suave y línea 1.5. El selector futuro reutiliza campo blanco, borde fino, radio de 8px, foco global y altura mínima de 48px. No se añadió un mundo visual ni una elevación de interfaz nueva. Son decisiones locales de extensión, sin convertir esta ficha en un patrón obligatorio para otras superficies.

## Evidencia y límites

- Código observado: `theme/fiesta-viva/costume-details.php`, `theme/fiesta-viva/assets/costume-sizes.js`, las dos copias JSON y el final de `theme/fiesta-viva/style.css`.
- Capturas iniciales: `.impeccable/review/ninja-desktop.png` (1265 × 2319) y `.impeccable/review/ninja-mobile.png` (375 × 3074), anteriores a la regla predeterminada de talles. La verificación posterior en navegador y `.impeccable/review/ninja-talle-unico.png` muestran «Talle único» con $1.635; medidas y contenido siguen pendientes.
- La revisión de implementación comunica validación del estado pendiente en navegador de escritorio y móvil, ausencia de compra y comprobación satisfactoria de sintaxis JavaScript.
- La rama de selección múltiple futura no fue probada en vivo. Las ramas de un talle y varios talles están documentadas por lectura de código, no por pruebas de disponibilidad real.
- No se realizó una auditoría formal de accesibilidad; etiquetas, selector nativo y región viva son evidencia de implementación, no certificación WCAG. El detector Impeccable sigue sin motor disponible según la revisión previa.

## Desfase de contexto observado

`PRODUCT.md` conserva frases anteriores sobre ausencia de fichas, fotos, precios y archivo de logo. El catálogo local actual y los registros posteriores aportan evidencia que vuelve esas frases incompletas. Se informa el desfase sin reparar archivos de contexto fuera del alcance. No cambia el estado pendiente de disponibilidad física, pagos, logística e integración con Hayden.
