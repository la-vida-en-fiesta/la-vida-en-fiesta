---
name: descargar
description: Descarga o actualiza el proyecto La Vida en Fiesta desde GitHub en la carpeta Documentos de Windows. Usar cuando Roberto pida descargar el proyecto, poner su copia al dia, $descargar o /descargar.
---

# Descargar La Vida en Fiesta

Ejecuta `scripts/descargar.ps1` con PowerShell, usando su ruta absoluta y sin parametros. El script detecta Documentos de Windows, incluso cuando esta redirigido a OneDrive, y usa la carpeta `La Vida en Fiesta`.

Repositorio: `https://github.com/la-vida-en-fiesta/la-vida-en-fiesta.git`.
Rama del proyecto: `master`. Mientras el PR hacia `main` no se integre, `main` solo contiene la presentacion del repositorio. No clones la rama predeterminada sin comprobar esto.

Si la carpeta no existe, clonala. Si ya existe, confirma al usuario que encontraste su copia y actualizala mediante fetch y avance rapido. El script comprueba la ruta del repositorio, el remoto, la rama, cambios locales y commits divergentes antes de modificar archivos.

No borres carpetas existentes, ni uses reset, clean, stash, rebase o push para resolver un bloqueo. Si hay cambios locales, otro repositorio, otra rama o divergencia, informa el motivo y conserva su copia. No repitas automaticamente un fallo de autenticacion o de red; explica como volver a intentar despues de resolverlo. No instales dependencias ni arranques la web salvo que el usuario lo pida.

Al terminar, informa si se clono, se actualizo o ya estaba al dia, la ruta absoluta y el commit. Una descarga del codigo no restaura bases de datos locales ni configura por si sola el sitio WordPress.

## Instalacion en otro equipo

La carpeta completa de esta skill se puede instalar ejecutando `scripts/instalar.ps1`. No requiere que el proyecto este clonado. El instalador permite elegir la carpeta de skills; sin parametros usa `$CODEX_HOME/skills` si esa variable existe, o `~/.agents/skills`.

La invocacion documentada en Codex es `$descargar` o seleccionar `descargar` en el menu de skills. El texto `/descargar` tambien describe este flujo si llega como mensaje, pero esta skill no registra un comando slash nuevo.
