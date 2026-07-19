# NagrikSathi - Admin Dashboard Install
# Run from: E:\nagriksathi-api\
# Usage: .\install_admin_dashboard.ps1

Write-Host ""
Write-Host "==========================================" -ForegroundColor Cyan
Write-Host "  Admin Dashboard - Install               " -ForegroundColor Cyan
Write-Host "==========================================" -ForegroundColor Cyan
Write-Host ""

# STEP 1: Check files
Write-Host "[1/6] Checking downloaded files..." -ForegroundColor Yellow
$files = @("SchemeResource.php", "SchemeCommandCenter.php", "SchemeStatsWidget.php", "scheme-command-center.blade.php")
foreach ($f in $files) {
    if (Test-Path $f) { Write-Host "  OK $f found in current dir" -ForegroundColor Green }
    else { Write-Host "  MISSING $f - copy it here first" -ForegroundColor Red }
}

# STEP 2: Copy files to correct locations
Write-Host ""
Write-Host "[2/6] Copying files to correct locations..." -ForegroundColor Yellow

# SchemeResource
if (Test-Path "SchemeResource.php") {
    Copy-Item "SchemeResource.php" "app\Filament\Resources\SchemeResource.php" -Force
    Write-Host "  DONE SchemeResource.php -> app\Filament\Resources\" -ForegroundColor Green
}

# SchemeCommandCenter page
if (!(Test-Path "app\Filament\Pages")) { New-Item -ItemType Directory -Path "app\Filament\Pages" | Out-Null }
if (Test-Path "SchemeCommandCenter.php") {
    Copy-Item "SchemeCommandCenter.php" "app\Filament\Pages\SchemeCommandCenter.php" -Force
    Write-Host "  DONE SchemeCommandCenter.php -> app\Filament\Pages\" -ForegroundColor Green
}

# SchemeStatsWidget
if (!(Test-Path "app\Filament\Widgets")) { New-Item -ItemType Directory -Path "app\Filament\Widgets" | Out-Null }
if (Test-Path "SchemeStatsWidget.php") {
    Copy-Item "SchemeStatsWidget.php" "app\Filament\Widgets\SchemeStatsWidget.php" -Force
    Write-Host "  DONE SchemeStatsWidget.php -> app\Filament\Widgets\" -ForegroundColor Green
}

# Blade view
$viewPath = "resources\views\filament\pages"
if (!(Test-Path $viewPath)) { New-Item -ItemType Directory -Path $viewPath -Force | Out-Null }
if (Test-Path "scheme-command-center.blade.php") {
    Copy-Item "scheme-command-center.blade.php" "$viewPath\scheme-command-center.blade.php" -Force
    Write-Host "  DONE scheme-command-center.blade.php -> resources\views\filament\pages\" -ForegroundColor Green
}

# STEP 3: Register widget and page in AdminPanelProvider
Write-Host ""
Write-Host "[3/6] Checking AdminPanelProvider..." -ForegroundColor Yellow

$providerPath = "app\Providers\Filament\AdminPanelProvider.php"
if (Test-Path $providerPath) {
    $provider = Get-Content $providerPath -Raw

    if ($provider -notmatch "SchemeStatsWidget") {
        Write-Host "  Adding SchemeStatsWidget to AdminPanelProvider..." -ForegroundColor Yellow
        Write-Host "  Please manually add these to your AdminPanelProvider.php:" -ForegroundColor White
        Write-Host "  ->widgets([" -ForegroundColor White
        Write-Host "      \App\Filament\Widgets\SchemeStatsWidget::class," -ForegroundColor White
        Write-Host "  ])" -ForegroundColor White
        Write-Host "  ->pages([" -ForegroundColor White
        Write-Host "      \App\Filament\Pages\SchemeCommandCenter::class," -ForegroundColor White
        Write-Host "  ])" -ForegroundColor White
    } else {
        Write-Host "  OK already registered" -ForegroundColor Green
    }
} else {
    Write-Host "  AdminPanelProvider not found at expected path" -ForegroundColor Yellow
    Write-Host "  Find your Filament panel provider and add:" -ForegroundColor White
    Write-Host "  ->widgets([\App\Filament\Widgets\SchemeStatsWidget::class])" -ForegroundColor White
    Write-Host "  ->pages([\App\Filament\Pages\SchemeCommandCenter::class])" -ForegroundColor White
}

# STEP 4: Check district column exists on schemes table
Write-Host ""
Write-Host "[4/6] Checking schemes table for district column..." -ForegroundColor Yellow
php artisan tinker --execute="echo Schema::hasColumn('schemes', 'district') ? 'YES' : 'NO';"

# STEP 5: Clear caches
Write-Host ""
Write-Host "[5/6] Clearing caches..." -ForegroundColor Yellow
php artisan config:clear
php artisan cache:clear
php artisan optimize:clear
php artisan filament:cache-components
Write-Host "  DONE" -ForegroundColor Green

# STEP 6: Verify
Write-Host ""
Write-Host "[6/6] Done! Visit:" -ForegroundColor Yellow
Write-Host "  http://localhost:8000/admin/schemes           - Enhanced scheme list" -ForegroundColor White
Write-Host "  http://localhost:8000/admin/scheme-commands  - Command Center" -ForegroundColor White
Write-Host ""
Write-Host "==========================================" -ForegroundColor Cyan