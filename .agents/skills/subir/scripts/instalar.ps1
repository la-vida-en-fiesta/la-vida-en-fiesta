[CmdletBinding()]
param([string]$SkillsDirectory)
$ErrorActionPreference = 'Stop'
if ([string]::IsNullOrWhiteSpace($SkillsDirectory)) {
    if ($env:CODEX_HOME) { $SkillsDirectory = Join-Path $env:CODEX_HOME 'skills' }
    else { $SkillsDirectory = Join-Path ([Environment]::GetFolderPath('UserProfile')) '.codex\skills' }
}
$source = [IO.Path]::GetFullPath((Join-Path $PSScriptRoot '..'))
$target = [IO.Path]::GetFullPath((Join-Path $SkillsDirectory 'subir'))
if ($source.TrimEnd('\') -ne $target.TrimEnd('\')) {
    if (Test-Path -LiteralPath $target) {
        Write-Output "La skill subir ya esta instalada en: $target. No se sobrescribio."
        return
    }
    New-Item -ItemType Directory -Path $SkillsDirectory -Force | Out-Null
    Copy-Item -LiteralPath $source -Destination $target -Recurse
}
Write-Output "Skill instalada en: $target"
Write-Output 'En Codex usa $subir; para preparar el PR sin integrar, usa $subir sin merge.'
