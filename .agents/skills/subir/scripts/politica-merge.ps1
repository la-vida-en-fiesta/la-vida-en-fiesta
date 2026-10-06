# Este archivo no realiza operaciones remotas; valida los datos recien leidos del PR.
function Get-SubirMergeDecision {
    [CmdletBinding()]
    param(
        [Parameter(Mandatory = $true)]$PullRequest,
        [Parameter(Mandatory = $true)][string]$ExpectedHead,
        [Parameter(Mandatory = $true)][string]$ExpectedBase,
        [Parameter(Mandatory = $true)][string]$BaseBranch
    )
    $reason = $null
    $required = @('url', 'state', 'isDraft', 'headRefOid', 'baseRefOid', 'baseRefName', 'mergeable', 'mergeStateStatus', 'reviewDecision', 'statusCheckRollup')
    foreach ($field in $required) {
        if (-not $PullRequest.PSObject.Properties[$field]) { $reason = "Falta el dato del PR: $field"; break }
    }
    if (-not $reason) {
        if ($ExpectedHead -notmatch '^[a-fA-F0-9]{40}$' -or $ExpectedBase -notmatch '^[a-fA-F0-9]{40}$') { $reason = 'Los commits validados no son SHA completos.' }
        elseif ($PullRequest.url -notmatch '^https://github\.com/la-vida-en-fiesta/la-vida-en-fiesta/pull/[1-9][0-9]*$') { $reason = 'El PR pertenece a otro repositorio.' }
        elseif ($PullRequest.state -ne 'OPEN') { $reason = 'El PR no esta abierto.' }
        elseif ($PullRequest.isDraft -isnot [bool] -or $PullRequest.isDraft) { $reason = 'El PR es borrador o su estado no esta confirmado.' }
        elseif ($PullRequest.baseRefName -ne $BaseBranch) { $reason = 'El destino no es la rama principal comprobada.' }
        elseif ($PullRequest.headRefOid -ne $ExpectedHead -or $PullRequest.baseRefOid -ne $ExpectedBase) { $reason = 'El head o la base cambiaron: hay que revisar y validar otra vez.' }
        elseif ($PullRequest.mergeable -ne 'MERGEABLE') { $reason = 'La ausencia de conflictos no esta confirmada.' }
        elseif ($PullRequest.reviewDecision -in @('CHANGES_REQUESTED', 'REVIEW_REQUIRED')) { $reason = 'Hay revisiones pendientes o cambios solicitados.' }
        elseif ($PullRequest.reviewDecision -notin @($null, '', 'APPROVED')) { $reason = 'La decision de las revisiones no se reconoce.' }
        elseif ($PullRequest.mergeStateStatus -ne 'CLEAN') { $reason = 'GitHub no confirma que el PR este listo para integrar.' }
    }
    if (-not $reason) {
        foreach ($check in @($PullRequest.statusCheckRollup)) {
            if ($null -eq $check) { continue }
            $type = $check.PSObject.Properties['__typename']
            if ($type -and $type.Value -eq 'CheckRun') {
                if ($check.status -ne 'COMPLETED' -or $check.conclusion -notin @('SUCCESS', 'NEUTRAL', 'SKIPPED')) {
                    $reason = 'Hay checks fallidos, cancelados o pendientes.'; break
                }
            } elseif ($type -and $type.Value -eq 'StatusContext') {
                if ($check.state -ne 'SUCCESS') { $reason = 'Hay estados de CI fallidos o pendientes.'; break }
            } else {
                $reason = 'Un check no tiene un formato reconocido.'; break
            }
        }
    }
    $ready = -not $reason
    if ($ready) { $reason = 'PR listo en los commits comprobados; requiere ademas validacion local satisfactoria.' }
    return [pscustomobject]@{ Ready = $ready; Reason = $reason }
}
