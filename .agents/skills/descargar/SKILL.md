---
name: descargar
description: Prepara Git si falta y descarga o actualiza La Vida en Fiesta en Documentos de Windows sin una cuenta de GitHub. Usar al pedir descargar el proyecto, poner su copia al dia, $descargar o /descargar.
---

# Descargar La Vida en Fiesta

Ejecuta `scripts/descargar.ps1` con PowerShell, usando su ruta absoluta y sin parametros. El script detecta Documentos de Windows, incluso cuando esta redirigido a OneDrive, y usa la carpeta `La Vida en Fiesta`.

La preparacion inicial esta incluida: reutiliza Git si esta disponible, incluso fuera del PATH, o instala Git for Windows con winget. Si winget no existe, descarga la version oficial compatible para x64/ARM64, verifica su firma y la instala para el usuario. No instala GitHub Desktop ni requiere configurar autor de commits.

Antes de ejecutarla, avisa que instalara Git si falta; la peticion de preparar y descargar el proyecto autoriza esa instalacion. Respeta cualquier restriccion real del equipo o del ejecutor: si Windows solicita permisos o rechaza la instalacion, informa el bloqueo y conserva los archivos; no eludas esos controles ni repitas una instalacion fallida.

El repositorio es publico. El script accede por HTTPS anonimo, desactiva las solicitudes de credenciales solo para sus comandos y comprueba la rama antes de crear una copia. No pidas cuenta, contraseña, token, nombre ni correo. Si en el futuro deja de ser publico, informa el cambio y detente; esta skill no configura cuentas ni autenticacion.

Repositorio: `https://github.com/la-vida-en-fiesta/la-vida-en-fiesta.git`.
Rama de descarga y trabajo: `master`. `main` es la rama principal donde se integran los PRs. Conserva la rama de descarga definida por el script para obtener las actualizaciones de trabajo.

Si la carpeta no existe, clonala. Si ya existe, confirma al usuario que encontraste su copia y actualizala mediante fetch y avance rapido. El script comprueba la ruta del repositorio, el remoto, la rama, cambios locales y commits divergentes antes de modificar archivos.

No borres carpetas existentes, ni uses reset, clean, stash, rebase o push para resolver un bloqueo. Si hay cambios locales, otro repositorio, otra rama o divergencia, informa el motivo y conserva su copia. No repitas automaticamente un fallo de acceso o de red; explica como volver a intentar despues de resolverlo. La preparacion cubre Git; no instales dependencias de la tienda ni arranques la web salvo que el usuario lo pida.

Al terminar, informa si se clono, se actualizo o ya estaba al dia, la ruta absoluta y el commit. Una descarga del codigo no restaura bases de datos locales ni configura por si sola el sitio WordPress.

La copia del repositorio incluye `.agents/skills/subir/`. Indica que Roberto debe abrir la carpeta descargada como proyecto en Codex y ejecutar `$subir` desde un chat de ese proyecto. No necesita instalar subir por separado ni copiarla a su carpeta personal. Las skills del repositorio solo se descubren al trabajar dentro de el; si no aparece tras actualizar, indica que abra un chat nuevo en ese proyecto.

## Instalacion en otro equipo

La carpeta completa de esta skill se puede instalar ejecutando `scripts/instalar.ps1`. No requiere que el proyecto este clonado. El instalador permite elegir la carpeta de skills; sin parametros usa `$CODEX_HOME/skills` si esa variable existe, o `~/.agents/skills`.

La invocacion documentada en Codex es `$descargar` o seleccionar `descargar` en el menu de skills. El texto `/descargar` tambien describe este flujo si llega como mensaje, pero esta skill no registra un comando slash nuevo.
