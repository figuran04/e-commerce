# setup_vhosts.ps1
# Script to configure hosts and print Apache Virtual Hosts setup.
# Run this script as Administrator in PowerShell.

# Check for Admin Privileges
$isAdmin = ([Security.Principal.WindowsPrincipal][Security.Principal.WindowsIdentity]::GetCurrent()).IsInRole([Security.Principal.WindowsBuiltInRole]::Administrator)
if (-not $isAdmin) {
    Write-Error "Please run this PowerShell script as Administrator!"
    Exit
}

# 1. Update hosts file
$hostsPath = "C:\Windows\System32\drivers\etc\hosts"
$domains = @("auth.zerovaa.com", "api.zerovaa.com")
$hostsContent = Get-Content $hostsPath -Raw

Write-Host "Checking hosts file entries..." -ForegroundColor Cyan
foreach ($domain in $domains) {
    $entry = "127.0.0.1 $domain"
    if ($hostsContent -notmatch $domain) {
        Add-Content $hostsPath "`n127.0.0.1 $domain"
        Write-Host "Added to hosts: $entry" -ForegroundColor Green
    } else {
        Write-Host "Already exists in hosts: $domain" -ForegroundColor Yellow
    }
}

# 2. Apache Virtual Hosts Configuration
$vhostConfig = @"

# =========================================================================
# Virtual Host Configuration for Zerovaa E-Commerce Phase 3
# =========================================================================

# 1. Fallback for localhost (Keep existing localhost path working)
<VirtualHost *:80>
    DocumentRoot "D:/xampp/xamp/htdocs/5/e-commerce"
    ServerName localhost
    <Directory "D:/xampp/xamp/htdocs/5/e-commerce">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>

# 2. Auth Service: auth.zerovaa.com
<VirtualHost *:80>
    DocumentRoot "D:/xampp/xamp/htdocs/5/e-commerce"
    ServerName auth.zerovaa.com
    <Directory "D:/xampp/xamp/htdocs/5/e-commerce">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>

# 3. Main/API Service: api.zerovaa.com
<VirtualHost *:80>
    DocumentRoot "D:/xampp/xamp/htdocs/5/e-commerce"
    ServerName api.zerovaa.com
    <Directory "D:/xampp/xamp/htdocs/5/e-commerce">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
"@

Write-Host "`n--- Apache Configuration ---" -ForegroundColor Cyan
Write-Host "Please append the following block to your Apache httpd-vhosts.conf file." -ForegroundColor White
Write-Host "(Usually located at: D:\xampp\xamp\apache\conf\extra\httpd-vhosts.conf)`n" -ForegroundColor White
Write-Host $vhostConfig -ForegroundColor Green

# Attempting to auto-locate and append if user permits
$xamppVhostsPath = "D:\xampp\xamp\apache\conf\extra\httpd-vhosts.conf"
if (Test-Path $xamppVhostsPath) {
    $currentVhosts = Get-Content $xamppVhostsPath -Raw
    if ($currentVhosts -notmatch "auth.zerovaa.com") {
        Add-Content $xamppVhostsPath "`n$vhostConfig"
        Write-Host "`nSuccessfully appended virtual host configuration to $xamppVhostsPath" -ForegroundColor Green
    } else {
        Write-Host "`nSubdomains already detected in $xamppVhostsPath" -ForegroundColor Yellow
    }
    Write-Host "`nIMPORTANT: Please restart your Apache Server in the XAMPP Control Panel for changes to take effect." -ForegroundColor Red
} else {
    Write-Host "`nCould not find Apache httpd-vhosts.conf at: $xamppVhostsPath" -ForegroundColor Red
    Write-Host "Please locate your XAMPP installation and copy the configuration manually." -ForegroundColor Red
}
