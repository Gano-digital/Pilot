# Publica el Gano Ops Hub en la rama huérfana gh-pages (GitHub Pages modo legacy).
# Uso (desde la raíz del repo):  pwsh -File scripts/publish-gano-ops-gh-pages.ps1
# Requiere: Python, git, remoto origin con permiso de push.

$ErrorActionPreference = "Stop"
$Root = Resolve-Path (Join-Path $PSScriptRoot "..")
Set-Location $Root

$pubSrc = Join-Path $Root "tools\gano-ops-hub\public"
if (-not (Test-Path (Join-Path $pubSrc "index.html"))) {
    Write-Error "No existe tools/gano-ops-hub/public/index.html"
}

$temp = Join-Path ([System.IO.Path]::GetTempPath()) ("gano-ops-pages-" + [Guid]::NewGuid().ToString("n"))
New-Item -ItemType Directory -Path $temp | Out-Null
try {
    & python (Join-Path $Root "scripts\generate_gano_ops_progress.py")
    if ($LASTEXITCODE -ne 0) { exit $LASTEXITCODE }
    Copy-Item -Path (Join-Path $pubSrc "*") -Destination $temp -Recurse

    $prev = git branch --show-current
    git fetch origin 2>$null
    git checkout gh-pages 2>$null
    if ($LASTEXITCODE -ne 0) {
        git checkout -b gh-pages origin/gh-pages 2>$null
    }
    if ($LASTEXITCODE -ne 0) {
        git checkout --orphan gh-pages
    }
    Get-ChildItem -Force $Root | Where-Object { $_.Name -ne ".git" } | Remove-Item -Recurse -Force
    Copy-Item -Path (Join-Path $temp "*") -Destination $Root -Recurse
    New-Item -Path (Join-Path $Root ".nojekyll") -ItemType File -Force | Out-Null
    git add -A
    $st = git status --porcelain
    if (-not $st) {
        Write-Host "Sin cambios respecto al último commit en gh-pages."
    } else {
        $ts = (Get-Date).ToUniversalTime().ToString("yyyy-MM-dd HH:mm")
        git commit -m "chore(pages): refresh Gano Ops Hub ($ts UTC)"
        git push -u origin gh-pages
    }
    git checkout $prev
}
finally {
    Remove-Item -Recurse -Force $temp -ErrorAction SilentlyContinue
}

Write-Host "Listo. URL prevista (repo público o Pages privado Enterprise): https://gano-digital.github.io/Pilot/"
