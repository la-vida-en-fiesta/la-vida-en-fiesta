# Descargar el proyecto en casa

La skill `descargar` copia La Vida en Fiesta en la carpeta Documentos de Windows (tambien reconoce Documentos en OneDrive). La primera vez clona GitHub; las siguientes confirma que ya existe y descarga las novedades de la rama `master`.

## Instalar una sola vez

En Codex, pide:

> Instala con skill-installer la skill de https://github.com/la-vida-en-fiesta/la-vida-en-fiesta/tree/master/.agents/skills/descargar

Esto permite instalarla sin descargar antes todo el proyecto. Si el repositorio es privado, necesitas tener acceso con tu cuenta de GitHub. Tambien necesitas Git instalado para clonar y actualizar.

Como alternativa, recibe el ZIP `descargar-roberto.zip`, extraelo y ejecuta en PowerShell desde la carpeta extraida:

```powershell
powershell -NoProfile -ExecutionPolicy Bypass -File .\descargar\scripts\instalar.ps1
```

La politica Bypass se limita a ese proceso: no cambia la configuracion del equipo.

## Usar en cualquier momento

En Codex, escribe **`$descargar`** o selecciona **descargar** en el menu de skills. Si no aparece tras instalarla, reinicia Codex.

El nombre solicitado es `descargar`; las skills no registran automaticamente un comando personalizado `/descargar`. La invocacion explicita que soporta Codex es `$descargar`.

La skill mostrara la carpeta y si se clono, se actualizo o ya estaba al dia. Si hay cambios locales, otra rama o commits que requieren revision, se detendra y conservara los archivos. No borra ni reemplaza tu trabajo.

Solo descarga el codigo; no arranca la tienda ni instala dependencias. Para ejecutar el entorno local, sigue el README del proyecto.
