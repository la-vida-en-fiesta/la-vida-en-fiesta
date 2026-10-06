# GitHub y permisos

Usa el conector GitHub existente cuando permita leer el repo, publicar PRs y realizar merge con el SHA revisado. Si falta esa capacidad, utiliza GitHub CLI; no presupongas que el token del conector esta disponible para Git o para `gh`.

## Preparar GitHub CLI cuando haga falta

Busca `gh` en PATH y en `Program Files\GitHub CLI\gh.exe`. Si no esta instalado, la peticion de preparar este flujo autoriza instalar esa herramienta. Anuncia la preparacion y usa:

```powershell
winget install --id GitHub.cli --exact --source winget --silent --accept-package-agreements --accept-source-agreements --disable-interactivity
```

Si no hay winget, consulta `https://api.github.com/repos/cli/cli/releases/latest` y descarga el ZIP oficial `gh_<version>_windows_amd64.zip` o `gh_<version>_windows_arm64.zip` para la arquitectura de Windows, junto con `gh_<version>_checksums.txt`. Acepta solo URLs HTTPS bajo `github.com/cli/cli/releases/download/`. Verifica el SHA256 exacto del ZIP contra esa lista antes de extraerlo a una carpeta nueva dentro de LocalAppData/LaVidaEnFiesta/Subir; usa la ruta absoluta de `bin/gh.exe`. No cambies el PATH global ni ejecutes paquetes de terceros. Si la descarga, verificacion o instalacion falla, informa y detente sin saltar controles del equipo.

## Autenticar una vez

```powershell
gh auth status --active --hostname github.com
gh auth login --hostname github.com --git-protocol https --web
gh auth setup-git --hostname github.com
gh repo view la-vida-en-fiesta/la-vida-en-fiesta --json defaultBranchRef,viewerPermission
```

Ejecuta login solo si no hay sesion valida; setup-git solo cuando necesitas conectar Git con esa sesion. Roberto completa el navegador y el codigo de un solo uso. No expongas tokens ni uses `--insecure-storage`. La cuenta debe tener permiso WRITE, MAINTAIN o ADMIN para publicar en este repo; no crees forks ni solicites mas privilegios automaticamente. El merge sigue dependiendo de protecciones y permisos reales.

Si Git necesita identidad y aun no tiene nombre/correo, `gh api user --jq '{login: .login, name: .name, id: .id}'` permite establecer nombre publico y el noreply de la cuenta (`<id>+<login>@users.noreply.github.com`). Configura solo `git config --local user.name ...` y `git config --local user.email ...`; conserva lo que Roberto ya tenga configurado.

## PR y merge

Todos los comandos usan el repo explicito. Sustituye los valores por datos obtenidos de Git/GitHub; no pegues los marcadores literalmente.

```powershell
gh pr list --repo la-vida-en-fiesta/la-vida-en-fiesta --state open --head RAMA --base PRINCIPAL --json number,url
gh pr create --repo la-vida-en-fiesta/la-vida-en-fiesta --head RAMA --base PRINCIPAL --title TITULO --body-file ARCHIVO_UTF8
gh pr edit URL_PR --repo la-vida-en-fiesta/la-vida-en-fiesta --title TITULO --body-file ARCHIVO_UTF8
gh pr view URL_PR --repo la-vida-en-fiesta/la-vida-en-fiesta --json url,state,isDraft,headRefOid,baseRefOid,baseRefName,mergeable,mergeStateStatus,reviewDecision,statusCheckRollup
```

Convierte el ultimo JSON en un objeto PowerShell y pasa el objeto a la politica, con los SHA completos de la revision y de la base probada:

```powershell
. 'RUTA_ABSOLUTA_SKILL/scripts/politica-merge.ps1'
$decision = Get-SubirMergeDecision -PullRequest $pr -ExpectedHead $headValidado -ExpectedBase $baseValidada -BaseBranch $ramaPrincipal
$decision
```

Solo con Ready=true y validacion local satisfactoria:

```powershell
gh pr merge URL_PR --repo la-vida-en-fiesta/la-vida-en-fiesta --merge --match-head-commit SHA_REVISADO
gh pr view URL_PR --repo la-vida-en-fiesta/la-vida-en-fiesta --json url,state,mergedAt,mergeCommit
```

No uses `--admin` ni `--delete-branch`. Un check pendiente requiere esperar o dejar el PR preparado, no dar por hecho que se integrara. Si el usuario pidio expresamente programar auto-merge, agrega `--auto` cuando el repo lo permita y distingue ese estado de MERGED. Si los permisos rechazan la operacion, conserva el PR y reporta el motivo.

Referencias oficiales: [autenticacion](https://cli.github.com/manual/gh_auth_login), [crear PR](https://cli.github.com/manual/gh_pr_create), [merge con SHA y requisitos](https://cli.github.com/manual/gh_pr_merge).
