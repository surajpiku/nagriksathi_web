# NagrikSathi - Scheme Fetchers Install Script
# Run from: E:\nagriksathi-api\
# Usage: .\install_scheme_fetchers.ps1

Write-Host ""
Write-Host "==========================================" -ForegroundColor Cyan
Write-Host "  Scheme Fetchers - Phase 1 Install       " -ForegroundColor Cyan
Write-Host "==========================================" -ForegroundColor Cyan
Write-Host ""

# STEP 1: Copy command files (download from Claude first)
Write-Host "[1/5] Checking command files..." -ForegroundColor Yellow

$commands = @(
    "FetchMySchemeData.php",
    "FetchDbtSchemes.php",
    "FetchNspScholarships.php",
    "ScrapeMinistryRss.php"
)

foreach ($cmd in $commands) {
    if (Test-Path "app\Console\Commands\$cmd") {
        Write-Host "  OK $cmd exists" -ForegroundColor Green
    } else {
        Write-Host "  MISSING $cmd - please copy from downloaded files" -ForegroundColor Red
    }
}

# STEP 2: Register commands in console.php scheduler
Write-Host ""
Write-Host "[2/5] Adding schedule entries to routes\console.php..." -ForegroundColor Yellow

$console = Get-Content "routes\console.php" -Raw

if ($console -notmatch "fetch-myscheme") {
    $scheduleBlock = "Schedule::command('opportunities:scrape-rss')->dailyAt('07:00');"
    $newEntries = "Schedule::command('opportunities:scrape-rss')->dailyAt('07:00');
Schedule::command('schemes:scrape-ministry-rss')->dailyAt('06:00');
Schedule::command('schemes:fetch-myscheme --limit=50')->weeklyOn(1, '05:00');
Schedule::command('schemes:fetch-dbt --count=30')->weeklyOn(3, '05:00');
Schedule::command('schemes:fetch-nsp --count=40')->weeklyOn(5, '05:00');"

    $console = $console -replace [regex]::Escape($scheduleBlock), $newEntries
    [System.IO.File]::WriteAllText("$PWD\routes\console.php", $console, [System.Text.UTF8Encoding]::new($false))
    Write-Host "  DONE schedule entries added" -ForegroundColor Green
} else {
    Write-Host "  SKIP already scheduled" -ForegroundColor Yellow
}

# STEP 3: Clear caches
Write-Host ""
Write-Host "[3/5] Clearing caches..." -ForegroundColor Yellow
php artisan config:clear
php artisan cache:clear
php artisan optimize:clear
Write-Host "  DONE" -ForegroundColor Green

# STEP 4: Verify commands registered
Write-Host ""
Write-Host "[4/5] Verifying commands..." -ForegroundColor Yellow
php artisan list | findstr "schemes"

# STEP 5: Run first fetch
Write-Host ""
Write-Host "[5/5] Running initial fetches..." -ForegroundColor Yellow

Write-Host "  Running schemes:fetch-myscheme..." -ForegroundColor White
php artisan schemes:fetch-myscheme --limit=50

Write-Host ""
Write-Host "  Running schemes:fetch-dbt..." -ForegroundColor White
php artisan schemes:fetch-dbt --count=30

Write-Host ""
Write-Host "  Running schemes:fetch-nsp..." -ForegroundColor White
php artisan schemes:fetch-nsp --count=40

Write-Host ""
Write-Host "  Running schemes:scrape-ministry-rss..." -ForegroundColor White
php artisan schemes:scrape-ministry-rss

Write-Host ""
Write-Host "==========================================" -ForegroundColor Cyan
Write-Host "  Done! Check scheme count:" -ForegroundColor Cyan
Write-Host "  php artisan tinker --execute=" -ForegroundColor White
Write-Host "  App\Models\Scheme::count()" -ForegroundColor White
Write-Host "==========================================" -ForegroundColor Cyan