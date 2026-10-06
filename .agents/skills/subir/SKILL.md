---
name: subir
description: Revisa los cambios de La Vida en Fiesta, comprueba compatibilidad con GitHub, hace commit y push, prepara el PR e integra en la rama principal si las verificaciones y permisos lo permiten. Usar al pedir subir cambios del proyecto, $subir o /subir.
---

# Subir La Vida en Fiesta

Repositorio autorizado: `https://github.com/la-vida-en-fiesta/la-vida-en-fiesta`.

Este flujo incluye revisar, comprobar, guardar, publicar y hacer merge cuando sea viable. Una invocacion humana de `$subir` o una peticion de ejecutar este flujo autoriza esas acciones sobre este repositorio; explica al empezar que intentaras integrar al pasar las comprobaciones. Respeta cualquier instruccion del usuario que limite el alcance: `$subir sin merge` termina con el PR listo. Crear o instalar esta skill por si solo no autoriza ejecutarla sobre un PR existente.

Usa las herramientas GitHub conectadas si cubren las operaciones; de lo contrario sigue [la preparacion y los comandos de GitHub CLI](references/github.md). No presupongas que una cuenta que puede leer el repositorio puede escribir: comprueba la cuenta y su permiso de escritura. La primera autenticacion necesita que Roberto complete el inicio de sesion en el navegador; no solicites sus contraseñas ni tokens en el chat.

## Revisar y comprobar compatibilidad

1. Localiza la copia indicada por el usuario. Si no indico una, usa el repositorio del directorio actual cuando su remoto sea el autorizado; si no, busca `La Vida en Fiesta` en Documentos de Windows. Verifica la raiz Git real y `origin` (HTTPS o SSH del mismo repositorio). Si no hay copia, pide ejecutar `$descargar`; no clones ni subas otra carpeta por aproximacion.
2. Lee las instrucciones locales y revisa estado, diff preparado, diff sin preparar y archivos nuevos relevantes. Incluye los cambios del usuario correspondientes al proyecto. No publiques `.local/`, bases de datos, `node_modules/`, `.env`, credenciales o artefactos temporales. Revisa tambien los archivos ya rastreados: `.gitignore` no los protege. No imprimas secretos al revisar.
3. Obtiene de GitHub la rama principal actual (hoy `main`) y hace fetch de esa rama y de la rama de trabajo si existe. Registra sus SHA. El proyecto se mantiene en `master` mientras su primer PR se integra; no confundas la presentacion inicial de `main` con el codigo de la tienda. Mantiene `master` si esa es la rama de trabajo existente. Si estas en la rama principal, crea una rama `codex/subir-<fecha-hora>` antes de guardar o publicar; no hagas push directo a la principal.
4. Comprueba lo nuevo en el remoto y revisa el conjunto completo que entrara en el PR, no solo el ultimo diff local. Antes de publicar incorpora mediante merge los commits nuevos de la rama remota de trabajo y de la principal, conservando el historial. Primero valida y guarda los cambios locales en un commit para no perderlos. Si hay conflictos, resuelve solo cuando el resultado correcto sea claro; si requiere una decision del usuario, conserva el trabajo y pide esa decision. No uses force push, reset, clean, stash automatico ni rebase de commits publicados. Si los historiales no comparten ancestro, detente y explica; no combines historiales ajenos automaticamente.
5. La ausencia de conflictos no demuestra compatibilidad funcional. Revisa las interfaces afectadas y ejecuta pruebas pertinentes sobre el resultado combinado. Usa [las comprobaciones de este proyecto](references/comprobaciones.md). Corrige fallos atribuibles a los cambios autorizados y vuelve a comprobar. Si falta una prueba material o una dependencia necesaria, explica la limitacion y deja el PR preparado sin merge automatico. No inventes una ejecucion ni declares PASS a partir de una inspeccion.

## Commit, push y PR

Si falta identidad Git, obten el nombre de la cuenta autenticada y su correo publico o noreply de GitHub; configura solo este repositorio y no inventes una identidad. Si esa informacion no se puede establecer, pide el nombre y correo que quiere usar. Conserva la configuracion existente.

Prepara los archivos revisados mediante rutas explicitas, revisa el diff final y ejecuta `git diff --cached --check`. Escribe un mensaje de commit que describa el resultado; no crees commits vacios. Conserva los commits existentes del usuario. Tras incorporar cambios remotos, repite las comprobaciones afectadas antes de hacer `git push -u origin <rama>` sin forzar. Si el remoto cambia durante el push, recupera los nuevos commits, integra y valida antes de un nuevo intento; tras dos rechazos consecutivos de concurrencia, deja de reintentar e informa.

Busca un PR abierto de esa misma rama hacia la principal; actualizalo en lugar de duplicarlo. Crea uno listo para revisar si no existe. Describe el cambio final, las comprobaciones ejecutadas y las limitaciones reales. Usa argumentos estructurados o un archivo temporal UTF-8 con `--body-file`. Adjunta siempre el PR al chat mediante `attach_artifact` si la herramienta esta disponible, tanto al crearlo como al actualizarlo.

Si no hay cambios locales ni commits nuevos para publicar, informa que ya esta al dia; no crees un PR vacio. Si hay un PR abierto de la rama de trabajo y el usuario pidio ejecutar el flujo completo, compruebalo antes de cualquier merge; no integres otros PRs del repositorio.

## Merge autorizado y comprobado

Antes de integrar, registra el SHA exacto del head revisado y el SHA de la principal con el que se comprobo la compatibilidad. Consulta de nuevo el PR: URL, estado, rama base, SHA del head y base, borrador, mergeabilidad, decision de revisiones y todos los checks. Con GitHub CLI usa los campos de [references/github.md](references/github.md).

Carga `scripts/politica-merge.ps1` y ejecuta `Get-SubirMergeDecision` con los datos reales, ambos SHA validados y la rama principal esperada. Solo intenta el merge si devuelve `Ready = true` y las comprobaciones locales relevantes pasaron. Si cambiaron los SHA, vuelve a revisar y validar antes de intentar; tras dos cambios consecutivos deja el PR preparado e informa.

Haz un merge normal con `--match-head-commit <SHA-revisado>`; no uses privilegios de administrador para eludir protecciones, apruebes tu propio PR, borres ramas ni cambies las reglas del repositorio. Si los checks estan pendientes, informa que el PR espera comprobaciones; si el usuario pidio explicitamente programar auto-merge, puedes usar `--auto`, conservando los requisitos de GitHub. No programes auto-merge cuando la validacion local falte o haya fallado.

Si faltan permisos, revisiones obligatorias, hay conflictos o fallos, deja el PR preparado y explica el motivo. Si GitHub no admite merge commits, usa otro metodo permitido solo si conserva adecuadamente los commits existentes; cuando la rama compartida es `master`, evita squash o rebase que obliguen a reescribir su historial: deja el PR listo y explica la restriccion.

Comprueba la respuesta final de GitHub: PR realmente integrado y SHA del merge, o PR aun abierto. No confundas la solicitud de merge con su ejecucion. Haz fetch para actualizar referencias locales; no elimines ni reemplaces el trabajo local ni cambies de rama para terminar.

Informa en español: cambios guardados, commit/rama publicada, enlace al PR, comprobaciones y resultado real del merge. La integracion del codigo en GitHub no equivale a desplegar la tienda.

## Instalacion

Esta skill viene incluida en `.agents/skills/subir/` dentro del repositorio. Al abrir la carpeta del proyecto en Codex se descubre sin instalacion separada. El instalador `scripts/instalar.ps1` es opcional, solo para quien quiera usarla tambien fuera de este proyecto. En Codex se invoca con `$subir` o desde el selector; una skill no registra por si sola un comando slash `/subir`.
