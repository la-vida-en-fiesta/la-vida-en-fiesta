---
name: Fiesta Viva — La Vida en Fiesta
description: Cotillón cercano y multicolor sobre una base crema, con títulos redondeados y accesos claros.
colors:
  berry: "#B51F59"
  berry-hover: "#901646"
  coral: "#FF775E"
  yellow: "#FFD75E"
  turquoise: "#5BD4C7"
  birthday-surface: "#FFE6DA"
  extras-surface: "#E4F6EF"
  cream: "#FFF9F1"
  ink: "#292334"
  white: "#fff"
  muted: "#61536a"
  border: "#d9cdd5"
  field-border: "#a99faa"
  extras-border: "#b4d1c9"
typography:
  display:
    fontFamily: "Fredoka, sans-serif"
    fontSize: "clamp(3rem,6.6vw,5.4rem)"
    fontWeight: 500
    lineHeight: 1.02
    letterSpacing: "-.035em"
  headline:
    fontFamily: "Fredoka, sans-serif"
    fontSize: "clamp(2rem,3.5vw,3.2rem)"
    fontWeight: 500
    lineHeight: 1.08
  section-heading:
    fontFamily: "Fredoka, sans-serif"
    fontSize: "clamp(1.8rem,3vw,2.5rem)"
    fontWeight: 500
    lineHeight: 1.15
  directory-title:
    fontFamily: "Fredoka, sans-serif"
    fontSize: "24px"
    lineHeight: 1.2
  body:
    fontFamily: "'Nunito Sans', sans-serif"
    fontSize: "18px"
    lineHeight: 1.6
  label:
    fontFamily: "'Nunito Sans', sans-serif"
    fontSize: "15px"
    fontWeight: 800
rounded:
  field-commerce: "8px"
  control: "10px"
  panel: "22px"
  round: "50%"
spacing:
  compact: "8px"
  small: "12px"
  mobile-gap: "16px"
  gap: "20px"
  medium: "24px"
  panel: "32px"
  large: "48px"
components:
  button-primary:
    backgroundColor: "{colors.berry}"
    textColor: "{colors.white}"
    rounded: "{rounded.control}"
    padding: "12px 22px"
  button-primary-hover:
    backgroundColor: "{colors.berry-hover}"
    textColor: "{colors.white}"
  input-search:
    backgroundColor: "{colors.white}"
    textColor: "{colors.ink}"
    rounded: "{rounded.control}"
    padding: "12px 16px"
  feature-birthday:
    backgroundColor: "{colors.birthday-surface}"
    textColor: "{colors.ink}"
    rounded: "{rounded.panel}"
    padding: "32px"
  feature-costume:
    backgroundColor: "{colors.berry}"
    textColor: "{colors.cream}"
    rounded: "{rounded.panel}"
    padding: "32px"
  category-row:
    textColor: "{colors.ink}"
    typography: "{typography.directory-title}"
    padding: "17px 0"
  extras-panel:
    backgroundColor: "{colors.extras-surface}"
    textColor: "{colors.ink}"
    rounded: "{rounded.panel}"
    padding: "40px"
---

# Design System: Fiesta Viva — La Vida en Fiesta

## Overview

**Creative North Star: "Fiesta Viva"**

Fiesta Viva es la identidad aprobada: alegre, cercana y multicolor. La base crema deja respirar los títulos y las imágenes; Fredoka aporta una voz redondeada y Nunito Sans mantiene claros los textos y acciones. El color distingue los accesos sin convertir toda la página en decoración.

Este registro describe el tema propio implementado en `theme/fiesta-viva`, conservando la dirección de `design/fiesta-viva.md`. Las ilustraciones actuales son SVG decorativos de celebraciones; no representan productos disponibles. La composición particular de la portada permanece en `design/portada.md`, no es una plantilla obligatoria para todo el sitio.

**Key Characteristics:**
- Base crema y texto tinta, con frambuesa para acciones.
- Títulos redondeados y texto de lectura clara.
- Paneles suaves y directorios abiertos con separadores finos.
- Ilustración festiva puntual, iconos de categoría con borde de sticker y flechas SVG de trazo sencillo.

## Colors

Una paleta cálida y multicolor combina acciones frambuesa con acentos claros usados en superficies e ilustración. Los valores normativos están en el frontmatter.

### Primary
- **Frambuesa (`berry`)**: marca, enlaces, botones y panel de disfraces. **Frambuesa profunda (`berry-hover`)**: hover de botones.

### Secondary
- **Coral (`coral`)**, **amarillo confeti (`yellow`)** y **turquesa (`turquoise`)**: detalles del isotipo e ilustraciones; amarillo también aparece en selección de texto.
- **Melocotón claro (`birthday-surface`)**: panel ilustrado de cumpleaños. **Menta clara (`extras-surface`)**: agrupación de complementos.

### Neutral
- **Crema (`cream`)**: fondo de página y texto del panel frambuesa.
- **Tinta (`ink`)**: texto principal. **Blanco (`white`)**: búsqueda y texto de botones.
- **Tinta suave (`muted`)**: introducciones y texto secundario.
- **Separador (`border`)**, **borde de campo (`field-border`)** y **separador menta (`extras-border`)**: límites discretos de filas y campos.

### Named Rules
**The Acento Legible Rule.** Los acentos claros se emplean como superficies o ilustración con tinta; las acciones principales usan frambuesa con texto blanco.

## Typography

**Display Font:** Fredoka, con fallback sans-serif. Fuente variable local, usada a peso 500.
**Body Font:** Nunito Sans, con fallback sans-serif. Fuente variable local; botones usan 700 y acciones/directorio de navegación 800.

**Character:** Títulos expresivos y redondeados, acompañados por texto sobrio. Los nombres de categorías y los títulos usan mayúsculas y minúsculas naturales; la portada final no lleva kickers ni cejas en mayúsculas.

### Hierarchy
- **Display**: bienvenida de portada; su tamaño y ajuste están en `typography.display`. En móvil cambia a `clamp(2.8rem,11vw,4rem)` y limita la línea a 10ch.
- **Headline**: h2 general; los paneles destacados usan `clamp(2rem,3.5vw,3rem)`, tracking `-.02em`, 34px hasta 900px y 38px hasta 700px.
- **Section heading**: encabezados del directorio y complementos; línea 1.15 y ancho de lectura acotado.
- **Directory title**: nombres de categorías; heredan la familia Fredoka y el peso normal del cuerpo. Complementos usan 22px en escritorio; ambos directorios usan 21px en móvil tras incorporar iconos.
- **Body**: lectura principal; 16px hasta 700px. Párrafos generales con máximo 68ch; introducción de portada 19px/1.5 en escritorio y 16px en móvil.
- **Label**: acciones de panel y enlaces secundarios. Botones heredan la tipografía del cuerpo, con peso 700.

### Named Rules
**The Dos Voces Rule.** Fredoka lleva títulos y nombres destacados; Nunito Sans lleva explicaciones, búsqueda y acciones.

## Layout

La anchura máxima compartida es 1200px. Cabecera y contenido usan márgenes automáticos y relleno lateral de 32px, que pasa a 20px hasta 700px. Los espacios separan bloques amplios y las filas de categorías mantienen una densidad más compacta.

La portada observada combina dos escaparates en columnas 1.8fr/1fr con separación de 20px; hasta 900px son 1.4fr/1fr y hasta 700px se apilan con 16px. El directorio cambia de dos columnas con 48px entre ellas a una columna. Complementos cambia de dos columnas con 56px a una; su relleno baja de 40px a 32px y luego a 28px 24px. Las secciones de directorio y complementos arrancan con unos 70–72px de separación en escritorio y 48px en móvil.

La cabecera deja la búsqueda visible: en móvil ocupa una segunda fila completa, con la marca y el catálogo arriba. El pie cambia a columna en móvil. Estas reglas describen los componentes existentes; futuras fichas de producto deben resolverse con el contenido real.

## Elevation & Depth

El tema no usa `box-shadow`. La profundidad procede de superficies de color, contraste y superposición de ilustración. El suelo tonal del SVG de cumpleaños pertenece a la ilustración, no es una sombra de interfaz. Los bordes finos organizan directorios y campos. Los iconos de categoría llevan `filter: drop-shadow(0 2px 0 #ffffff)` exclusivamente como borde blanco de sticker; este recurso no se aplica a tarjetas ni controles.

El hover de los paneles mueve su ilustración `translateY(-5px) rotate(2deg)` con transición `transform .25s ease` y subraya la acción. Con movimiento reducido se desactivan transición y transformación, además del scroll suave.

### Named Rules
**The Profundidad por Color Rule.** Usar la separación tonal del tema para paneles; conservar los directorios planos con separadores.

## Shapes

Paneles grandes con esquinas suaves (`rounded.panel`); controles y búsqueda con radio `rounded.control`; campos de comercio con `rounded.field-commerce`. La flecha de cada escaparate se encierra en un círculo de 36px con borde fino del color actual. Los directorios son filas abiertas, sin convertir cada categoría en una tarjeta.

## Components

### Buttons
- Frambuesa, texto blanco, peso 700 y relleno `12px 22px`; hover frambuesa profunda.
- Foco visible global: contorno frambuesa de 3px, separado 5px del elemento.
- Deshabilitado: opacidad .55 y cursor de no disponible. El acceso a Instagram usa 16px, relleno `15px 24px` y flecha SVG.

### Cards / Containers
- Los escaparates son enlaces completos; radio suave, relleno de 32px y mínimo de 365px en escritorio. Bajo 900px pasan a 24px y 350px; en móvil cumpleaños conserva 315px y disfraces 285px.
- Texto e ilustración se colocan por separado; la acción queda al pie con márgenes propios. El panel frambuesa usa texto crema y el melocotón texto tinta.
- Complementos reutiliza radio de panel, fondo menta y filas con su separador menta.

### Inputs / Fields
- Búsqueda sobre blanco, texto tinta, borde de 1px y relleno `12px 16px`; placeholder tinta suave.
- Etiqueta accesible «Buscar productos» y botón «Buscar» visibles funcionalmente en el formulario de cabecera.
- Campos WooCommerce tienen borde fino, relleno de 12px y radio de 8px. No hay un sistema propio de errores documentado ni una validación completa de checkout.

### Navigation
- Catálogo subrayado y en peso 800; adaptación PNG transparente del logo oficial junto al nombre tipográfico frambuesa. Conserva su proporción cuadrada, a 84px en escritorio y 64px en móvil. Letras en tinta oscura para el fondo crema; el original JPG se conserva.
- Directorios con nombres Fredoka, icono decorativo, separadores finos, flecha SVG frambuesa y cambio de texto a frambuesa en hover. Las filas generales tienen mínimo de 72px y las de complementos 60px; directorio móvil, 68px.
- El salto al contenido se hace visible al recibir foco. El enlace a Instagram anuncia que abre una nueva pestaña.

### Flecha de acceso

SVG propio de 20px, trazo 1.7, extremos y uniones redondeados, `currentColor`, decorativo y no enfocable. Se reutiliza en escaparates, directorios, catálogo e Instagram; no es un glifo de texto.

### Iconos de celebración

Nueve SVG propios en `fiesta_category_icon()` representan globos, anillos, calabaza, árbol, vinilo, perfume, regalo, estrella sonriente y joya. Usan la paleta del tema, áreas de color planas y pequeños reflejos claros. Son decorativos (`aria-hidden="true"`, `focusable="false"`): el texto de categoría conserva el significado del enlace. Tamaño de 44px, reducido a 36px en móvil; separación del nombre de 16px, reducida a 12px.

`assets/birthday.svg` y `assets/costume.svg` reutilizan esa voz de sticker con bordes crema claros en globos y máscara. La referencia aportada por el usuario fue el logo circular con globos de `C:/Users/59899/Downloads/593.jpg` y la lámina de iconos festivos de `C:/Users/59899/Downloads/21244.jpg`. El usuario confirmó que 593.jpg es el logo oficial: se conserva una copia intacta en assets/logo-la-vida-en-fiesta.jpg. La cabecera usa la recreación transparente solicitada por el usuario, assets/logo-la-vida-en-fiesta.png; su procedencia y prompt están en design/logo-transparente.md. La segunda imagen sigue como referencia de iconografía.

## Do's and Don'ts

### Do:
- **Do** conservar Fiesta Viva y sus fuentes locales al extender el tema propio.
- **Do** reservar los colores claros para superficies e ilustración y comprobar contraste en nuevos estados.
- **Do** mantener nombres, búsqueda y destinos legibles en escritorio y móvil.
- **Do** reutilizar la flecha SVG, el foco visible y la respuesta a movimiento reducido.

### Don't:
- **Don't** añadir cejas decorativas en mayúsculas ni usar glifos como iconos de acceso.
- **Don't** presentar ilustraciones como fotos de productos reales ni inventar precios, stock o promociones.
- **Don't** convertir la composición específica de la portada en una obligación para todas las páginas.
- **Don't** tratar este registro visual como certificación de accesibilidad o de integración comercial.

<!-- Evidence: theme/fiesta-viva/style.css, header.php, front-page.php, functions.php, assets/birthday.svg, assets/costume.svg; .impeccable/review/desktop.png and mobile.png. No full WCAG conformance claimed. Commerce catalog, payments, logistics, Hayden integration and production hosting remain pending per PRODUCT.md. -->
