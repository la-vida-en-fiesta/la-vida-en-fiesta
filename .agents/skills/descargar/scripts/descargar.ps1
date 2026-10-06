[CmdletBinding()]
param(
    # Estos parametros permiten probar el flujo en repositorios aislados.
    [string]$DocumentsPath = [Environment]::GetFolderPath('MyDocuments'),
    [string]$RepositoryUrl = 'https://github.com/la-vida-en-fiesta/la-vida-en-fiesta.git',
    [string]$Branch = 'master'
)

$ErrorActionPreference = 'Stop'
Set-StrictMode -Version Latest
. (Join-Path $PSScriptRoot 'preparar-git.ps1')

function Invoke-ProjectGit {
    param([string[]]$GitArgs)
    $previousPrompt = $env:GIT_TERMINAL_PROMPT
    try {
        $env:GIT_TERMINAL_PROMPT = '0'
        # El proyecto es publico: no usar credenciales guardadas ni pedir login.
        $result = & $script:gitExecutable -c credential.helper= -c core.askPass= -c credential.interactive=never @GitArgs
        if ($LASTEXITCODE -ne 0) {
            throw "Git no pudo completar la operacion (codigo $LASTEXITCODE). Revisa la conexion, la rama y que el repositorio siga siendo publico. Esta skill no solicita una cuenta de GitHub."
        }
        return $result
    } finally {
        $env:GIT_TERMINAL_PROMPT = $previousPrompt
    }
}

function Get-RepositoryIdentity {
    param([string]$Value)
    $normalized = $Value.Trim().TrimEnd('/').ToLowerInvariant()
    $normalized = $normalized -replace '^git@github\.com:', 'https://github.com/'
    $normalized = $normalized -replace '^ssh://git@github\.com/', 'https://github.com/'
    return $normalized -replace '\.git$', ''
}

try {
    $script:gitExecutable = Ensure-ProjectGit
    if ([string]::IsNullOrWhiteSpace($DocumentsPath)) {
        throw 'Windows no pudo localizar Documentos. No se creo ninguna carpeta.'
    }
    $documents = [IO.Path]::GetFullPath($DocumentsPath)
    $destination = Join-Path $documents 'La Vida en Fiesta'

    if (-not (Test-Path -LiteralPath $destination)) {
        Write-Output 'Comprobando acceso al repositorio sin iniciar sesion en GitHub...'
        Invoke-ProjectGit -GitArgs @('ls-remote', '--exit-code', '--', $RepositoryUrl, "refs/heads/$Branch") | Out-Null
        New-Item -ItemType Directory -Path $documents -Force | Out-Null
        Write-Output "Descargando La Vida en Fiesta en: $destination"
        Invoke-ProjectGit -GitArgs @('clone', '--branch', $Branch, '--single-branch', '--', $RepositoryUrl, $destination) | Out-Host
        $state = 'clonado'
    } else {
        Write-Output "La copia del proyecto ya existe en: $destination"
        if (-not (Test-Path -LiteralPath (Join-Path $destination '.git'))) {
            throw 'La carpeta existente no es un repositorio Git. Se conservaron todos sus archivos.'
        }
        $repoRoot = Invoke-ProjectGit -GitArgs @('-C', $destination, 'rev-parse', '--show-toplevel')
        if ([IO.Path]::GetFullPath([string]$repoRoot).TrimEnd('\', '/') -ne $destination.TrimEnd('\', '/')) {
            throw 'La carpeta pertenece a otro repositorio. No se modifico.'
        }
        $origin = Invoke-ProjectGit -GitArgs @('-C', $destination, 'remote', 'get-url', 'origin')
        if ((Get-RepositoryIdentity ([string]$origin)) -ne (Get-RepositoryIdentity $RepositoryUrl)) {
            throw 'El remoto origin apunta a otro repositorio. No se modifico la copia.'
        }
        $currentBranch = Invoke-ProjectGit -GitArgs @('-C', $destination, 'branch', '--show-current')
        if ($currentBranch -ne $Branch) {
            throw "La copia esta en otra rama ($currentBranch). La rama esperada es $Branch. No se cambio de rama."
        }
        $changes = @(Invoke-ProjectGit -GitArgs @('-C', $destination, 'status', '--porcelain', '--untracked-files=all'))
        if ($changes.Count -gt 0) {
            throw 'Hay cambios locales sin guardar en un commit. Se conservaron; guardalos o revisalos antes de actualizar.'
        }
        Invoke-ProjectGit -GitArgs @('-C', $destination, 'fetch', 'origin', "+refs/heads/${Branch}:refs/remotes/origin/${Branch}") | Out-Host
        $before = Invoke-ProjectGit -GitArgs @('-C', $destination, 'rev-parse', 'HEAD')
        $remoteHead = Invoke-ProjectGit -GitArgs @('-C', $destination, 'rev-parse', "refs/remotes/origin/$Branch")
        if ($before -eq $remoteHead) {
            $state = 'ya estaba al dia'
        } else {
            & $script:gitExecutable -C $destination merge-base --is-ancestor HEAD "refs/remotes/origin/$Branch"
            if ($LASTEXITCODE -ne 0) {
                throw 'Hay commits locales que no estan en la version remota. Se conservaron; revisa las versiones antes de actualizar.'
            }
            Invoke-ProjectGit -GitArgs @('-C', $destination, 'merge', '--ff-only', "refs/remotes/origin/$Branch") | Out-Host
            $state = 'actualizado'
        }
    }
    $commit = Invoke-ProjectGit -GitArgs @('-C', $destination, 'rev-parse', '--short', 'HEAD')
    Write-Output "Proyecto $state. Carpeta: $destination. Rama: $Branch. Commit: $commit."
    if (Test-Path -LiteralPath (Join-Path $destination '.agents\skills\subir\SKILL.md')) {
        Write-Output ('La skill subir viene incluida. Abre esta carpeta como proyecto en Codex y usa $subir: ' + $destination)
    }
} catch {
    Write-Error $_.Exception.Message -ErrorAction Continue
    exit 1
}
