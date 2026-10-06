# Guardar y subir los cambios desde casa

Instala una vez la skill en Codex:

> Instala con skill-installer la skill de https://github.com/la-vida-en-fiesta/la-vida-en-fiesta/tree/master/.agents/skills/subir

Despues de hacer cambios en el proyecto, escribe **`$subir`**. Codex revisara el codigo y su compatibilidad con lo que ya esta en GitHub, ejecutara comprobaciones pertinentes, hara commit y push y creara o actualizara el PR hacia la rama principal.

El flujo intenta hacer **merge automaticamente** cuando las comprobaciones pasan, no hay conflictos ni revisiones pendientes y tu cuenta tiene permisos. Ejecutar `$subir` autoriza este flujo completo. Para subir y dejar el PR listo sin integrarlo, escribe **`$subir sin merge`**.

Descargar el proyecto publico no necesita cuenta; **subir o integrar cambios si requiere una cuenta de GitHub con acceso de escritura**. La primera vez, la skill preparara GitHub CLI si hace falta y te guiara para iniciar sesion en el navegador. Debes aceptar la invitacion al repositorio si no tienes acceso; la skill no puede darte permisos que no tengas. No compartas tu contraseña ni tokens en el chat.

Si hay conflictos, pruebas fallidas, revisiones obligatorias o un bloqueo de permisos, Codex conservara tu trabajo y explicara lo que falta. No fuerza el push, no borra ramas ni cambia las protecciones de GitHub.

Al terminar veras el commit, la rama, el enlace al PR y si el merge se ejecuto o quedo pendiente. El merge guarda los cambios en la rama principal; no publica por si solo la tienda en Internet.

En Codex las skills se invocan con `$subir` o seleccionandolas en su menu. El nombre es `subir`; no se registra automaticamente un comando slash personalizado `/subir`.
