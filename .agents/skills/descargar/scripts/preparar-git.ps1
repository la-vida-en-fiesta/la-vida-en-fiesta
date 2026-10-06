# Funciones separadas para comprobar la preparacion sin instalar nada al cargarlas.
function Find-ProjectGit {
    $command = Get-Command git -CommandType Application -ErrorAction SilentlyContinue | Select-Object -First 1
    if ($command) { return $command.Source }
    $roots = @($env:ProgramFiles, ${env:ProgramFiles(x86)})
    if ($env:LOCALAPPDATA) { $roots += (Join-Path $env:LOCALAPPDATA 'Programs') }
    foreach ($root in $roots) {
        if ($root) {
            $candidate = Join-Path $root 'Git\cmd\git.exe'
            if (Test-Path -LiteralPath $candidate -PathType Leaf) { return $candidate }
        }
    }
    return $null
}

function Install-ProjectGitWithWinget {
    $winget = Get-Command winget -CommandType Application -ErrorAction SilentlyContinue | Select-Object -First 1
    if (-not $winget) { return $false }
    Write-Host 'Git no esta instalado. Instalando Git for Windows con winget...'
    & $winget.Source install --id Git.Git --exact --source winget --silent --accept-package-agreements --accept-source-agreements --disable-interactivity | Out-Host
    if ($LASTEXITCODE -ne 0) {
        throw "winget no pudo instalar Git (codigo $LASTEXITCODE). Revisa el aviso de Windows o los permisos y vuelve a intentar. No se ejecutara otro instalador tras este fallo."
    }
    return $true
}

function Get-ProjectGitInstallerAsset {
    $architecture = $env:PROCESSOR_ARCHITEW6432
    if (-not $architecture) { $architecture = $env:PROCESSOR_ARCHITECTURE }
    switch ($architecture) {
        'AMD64' { $pattern = 'Git-*-64-bit.exe' }
        'ARM64' { $pattern = 'Git-*-arm64.exe' }
        default { throw "La instalacion automatica requiere Windows x64 o ARM64 (detectado: $architecture)." }
    }
    $release = Invoke-RestMethod -Uri 'https://api.github.com/repos/git-for-windows/git/releases/latest' -Headers @{ 'User-Agent' = 'LaVidaEnFiesta-descargar'; 'Accept' = 'application/vnd.github+json' } -TimeoutSec 30
    $assets = @($release.assets | Where-Object { $_.name -like $pattern })
    if ($assets.Count -ne 1 -or $assets[0].browser_download_url -notmatch '^https://github\.com/git-for-windows/git/releases/download/[^/]+/Git-[^/]+\.exe$') {
        throw 'No se encontro un instalador oficial compatible de Git. No se ejecuto ninguna descarga.'
    }
    return $assets[0]
}

function Assert-ProjectGitInstaller {
    param([string]$Path, $Asset)
    $signature = Get-AuthenticodeSignature -LiteralPath $Path
    if ($signature.Status -ne 'Valid') {
        throw 'La firma del instalador de Git no es valida. No se ejecuto el archivo.'
    }
    $digest = $Asset.PSObject.Properties['digest']
    if ($digest -and $digest.Value -match '^sha256:([a-fA-F0-9]{64})$') {
        $expected = $Matches[1]
        if ((Get-FileHash -LiteralPath $Path -Algorithm SHA256).Hash -ne $expected) {
            throw 'El archivo de Git no coincide con la descarga oficial. No se ejecuto.'
        }
    }
}

function Install-ProjectGitFromOfficialRelease {
    if (-not $env:LOCALAPPDATA) { throw 'Windows no pudo localizar la carpeta de aplicaciones del usuario.' }
    Write-Host 'winget no esta disponible. Descargando el instalador oficial firmado de Git for Windows...'
    # Windows PowerShell 5.1 puede usar protocolos antiguos de forma predeterminada.
    [Net.ServicePointManager]::SecurityProtocol = [Net.ServicePointManager]::SecurityProtocol -bor [Net.SecurityProtocolType]::Tls12
    $asset = Get-ProjectGitInstallerAsset
    $downloadFolder = Join-Path $env:LOCALAPPDATA 'LaVidaEnFiesta\Descargar'
    New-Item -ItemType Directory -Path $downloadFolder -Force | Out-Null
    $installer = Join-Path $downloadFolder ('git-installer-' + [guid]::NewGuid().ToString('N') + '.exe')
    Invoke-WebRequest -Uri $asset.browser_download_url -OutFile $installer -UseBasicParsing -TimeoutSec 300
    Assert-ProjectGitInstaller -Path $installer -Asset $asset
    $installFolder = Join-Path $env:LOCALAPPDATA 'Programs\Git'
    $process = Start-Process -FilePath $installer -ArgumentList @('/CURRENTUSER', '/VERYSILENT', '/NORESTART', '/SP-', ('/DIR="' + $installFolder + '"')) -WindowStyle Hidden -Wait -PassThru
    if ($process.ExitCode -ne 0) {
        throw "La instalacion oficial de Git fallo (codigo $($process.ExitCode)). Revisa los permisos de Windows antes de volver a intentar."
    }
}

function Ensure-ProjectGit {
    $gitPath = Find-ProjectGit
    if (-not $gitPath) {
        if (-not (Install-ProjectGitWithWinget)) { Install-ProjectGitFromOfficialRelease }
        $gitPath = Find-ProjectGit
        if (-not $gitPath) { throw 'La instalacion termino, pero Git no se encontro. Reinicia Codex y vuelve a ejecutar descargar.' }
    }
    $version = & $gitPath --version
    if ($LASTEXITCODE -ne 0) { throw 'Git se encontro pero no funciona. Revisa su instalacion.' }
    Write-Host "Git listo: $version. No hace falta una cuenta de GitHub para este repositorio publico."
    return $gitPath
}
