param (
    [Parameter(Mandatory=$true)]
    [ValidateSet("lint", "fix")]
    $Action
)

$env:PHP_CS_FIXER_IGNORE_ENV = 1

function Run-Lint {
    Write-Host "`n>>> 1. PHP style check..." -ForegroundColor Cyan
    php vendor/bin/php-cs-fixer fix --dry-run --diff

    Write-Host "`n>>> 2. PHP static analysis (Stan)..." -ForegroundColor Cyan
    php vendor/bin/phpstan analyse src --memory-limit=1G

    Write-Host "`n>>> 3. JS style check..." -ForegroundColor Cyan
    npm run lint
}

function Run-Fix {
    Write-Host "`n>>> 1. Cleaning of PHP code..." -ForegroundColor Green
    php vendor/bin/php-cs-fixer fix

    Write-Host "`n>>> 2. JS Formatting..." -ForegroundColor Green
    npm run format
}

if ($Action -eq "lint") { Run-Lint }
if ($Action -eq "fix") { Run-Fix }