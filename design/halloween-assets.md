# Recursos de Halloween

Generados con la herramienta integrada image_gen, con transparencia real. Las fotos del local son originales del usuario, sin recreación.

## Logo
Archivo: `theme/fiesta-viva/assets/logo-halloween.png`
Origen de edición: `C:/Users/59899/Downloads/81800.jpg`

Prompt exacto:

Edit target: supplied official Halloween logo of La Vida en Fiesta. Task background-extraction: recreate this same logo as a crisp transparent PNG cutout for a website header. Preserve the circular gold/orange ring, orange jack-o-lantern balloon, black and purple patterned balloons, witch hat, two ghosts, pumpkins, elegant handwritten LVF and exact text LA VIDA EN FIESTA. Preserve composition and identity. Remove the black background entirely including inside the ring, and remove background smoke/haze. Keep the black filled balloons and hat opaque. Actual alpha transparency everywhere else, no checkerboard baked in, no white plate. Tight clean edges and compact restrained gold highlights, legible lettering, no new words or objects.

## Decoración
Archivo: `theme/fiesta-viva/assets/halloween-decoration.png`
Referencia de estilo: mismo logo del usuario.

Prompt exacto:

Reference image is the Halloween brand illustration, style reference only. Generate a single standalone decorative Halloween cluster for La Vida en Fiesta website, actual transparent PNG. Three glossy orange jack-o-lantern pumpkins, one purple witch hat, one small friendly ghost and three black bats floating above, in the warm gold/orange and purple festive polished illustrated style of the reference. Compact composition centered, all objects fully visible with transparent padding, no ring, no balloons, no text, no logo, no background, no smoke, no ground plane. Gentle restrained gold sparkle accents, crisp clean cutout, high detail, friendly party mood rather than horror. This one cluster will be used as the Halloween category card art and small marginal decorations on cream webpages.

## Fotografías
`local-vidriera.jpg`: original 69405.jpg proporcionado por el usuario.
`local-detalle.jpg`: original 69802.jpg proporcionado por el usuario.

## Temporada
`seasons.php` activa campañas anuales usando America/Montevideo; ver actualización debajo. Fuera de campaña retorna Fiesta Viva y Cumpleaños como acceso alternativo.


## Calabaza de entrada
Archivo: `theme/fiesta-viva/assets/halloween-intro-pumpkin.png`
Generada con image_gen integrado; referencia de estilo: 81800.jpg.

Prompt exacto:

Style reference: supplied La Vida en Fiesta Halloween logo. Create a single centered front-facing glossy orange jack-o-lantern pumpkin with short curved brown stem for a website Halloween entrance animation. Actual transparent PNG background, one pumpkin only, fills 80 percent of square canvas. A large wide open laughing black mouth with two triangular teeth, clearly hollow dark center, glowing triangular amber eyes, friendly mischievous witchlike grin. The mouth center at 50 percent horizontal and 65 percent vertical for a camera zoom into the mouth. Warm orange/gold and subtle purple reflections, detailed polished Halloween party illustration matching reference, no words, no logo, no additional objects, no background, no smoke, clean alpha edges.

## Actualización del calendario y experiencia
Campañas iniciales editables en seasons.php: Nostalgia 1–24 agosto; Halloween 1–31 octubre; Navidad 25 noviembre–25 diciembre. Fuera de esas ventanas vuelve Fiesta Viva. Las fechas de inicio son propuestas de implementación, ajustables al calendario comercial del local.
Toda la tienda cambia paleta durante la campaña, incluidos catálogo, ficha, carrito y checkout. Halloween tiene logo propio, guardas de calabazas en el buscador, humo tenue y brujita en vuelo. Navidad y Nostalgia tienen paletas iniciales y categorías; todavía usan ilustraciones del tema base, sin sus propias entradas cinematográficas.

La intro vive sólo en portada, una vez por sesión, y no al entrar directamente a catálogo/checkout o al ancla del local. Es un dialog nativo con Escape y Saltar intro, y puede repetirse desde el pie. Secuencia de 2,57 segundos al entrar: risa (1s), zoom a boca (1,1s), disolver a tienda (0,47s). Risa sintetizada localmente con Web Audio, sólo por gesto en Entrar con sonido; sin archivos externos. Si el navegador bloquea audio, la secuencia visual sigue. Sin sonido no crea AudioContext.
Respeta movimiento reducido por defecto; los efectos pueden activarse voluntariamente desde el control. Pausa de efectos se conserva durante la sesión. Las animaciones se suspenden con pestaña oculta. No se aplican animaciones de entrada al catálogo ni al pago.


