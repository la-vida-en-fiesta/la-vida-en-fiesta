# Descargar el proyecto en casa

La skill `descargar` copia La Vida en Fiesta en la carpeta Documentos de Windows (tambien reconoce Documentos en OneDrive). La primera vez clona GitHub; las siguientes confirma que ya existe y descarga las novedades de la rama `master`.

## Instalar una sola vez

En Codex, pide:

> Instala con skill-installer la skill de https://github.com/la-vida-en-fiesta/la-vida-en-fiesta/tree/master/.agents/skills/descargar

Esto permite instalarla sin descargar antes todo el proyecto. El repositorio es publico: **no necesitas una cuenta de GitHub ni haberlo usado antes**. Si todavia no tienes Git, la propia skill lo prepara la primera vez.

Como alternativa, recibe el ZIP `descargar-roberto.zip`, extraelo y ejecuta en PowerShell desde la carpeta extraida:

```powershell
powershell -NoProfile -ExecutionPolicy Bypass -File .\descargar\scripts\instalar.ps1
```

La politica Bypass se limita a ese proceso: no cambia la configuracion del equipo.

## Usar en cualquier momento

En Codex, escribe **`$descargar`** o selecciona **descargar** en el menu de skills. Si no aparece tras instalarla, reinicia Codex.

El nombre solicitado es `descargar`; las skills no registran automaticamente un comando personalizado `/descargar`. La invocacion explicita que soporta Codex es `$descargar`.

La skill mostrara la carpeta y si se clono, se actualizo o ya estaba al dia. Si hay cambios locales, otra rama o commits que requieren revision, se detendra y conservara los archivos. No borra ni reemplaza tu trabajo.

## Subir viene incluida

Al terminar de descargar o actualizar, **`$descargar` instala automaticamente la skill `subir` en tu carpeta personal de Codex**. No tienes que instalarla por separado ni abrir primero la carpeta del proyecto para que aparezca. Si ya estaba instalada, la reconoce y conserva esa instalacion.

En tu siguiente mensaje puedes usar **`$subir`**. Para dejar solo el PR preparado, usa **`$subir sin merge`**. Si no aparece en el selector, reinicia Codex. La copia de la skill tambien viene dentro de `.agents/skills/subir/` en el repositorio.

## Preparacion automatica de la computadora

Al ejecutar `$descargar`, la skill:

1. Busca Git instalado, incluso si todavia no aparece en el PATH de Codex.
2. Si falta, instala Git for Windows con winget. Si no hay winget, usa el instalador oficial firmado para Windows x64 o ARM64, instalado para tu usuario.
3. Comprueba el acceso publico a la rama del proyecto sin pedir usuario, contraseña ni token.
4. Clona en Documentos, o actualiza la copia existente sin borrar tus cambios.
5. Instala automaticamente `subir` en `$CODEX_HOME/skills` o, si esa variable no existe, en `~/.codex/skills`.

Necesitas conexion a Internet y Codex instalado para ejecutar la skill. Si Windows solicita permisos para instalar Git, debes aceptar el aviso del sistema. Si una politica del equipo impide instalarlo, la skill te informara y se detendra. No configura cuentas de GitHub ni modifica tu nombre o correo de Git.

La instalacion inicial de la propia skill se hace una sola vez con las instrucciones anteriores; Git se prepara al ejecutarla. No hace falta GitHub Desktop.

Referencias: [instalacion oficial de Git para Windows](https://git-scm.com/install/windows) y [winget de Microsoft](https://learn.microsoft.com/en-us/windows/package-manager/winget/install).

Solo descarga el codigo; no arranca la tienda ni instala dependencias. Para ejecutar el entorno local, sigue el README del proyecto.
