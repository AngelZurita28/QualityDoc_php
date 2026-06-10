# =================================================================
# Script de Configuracion Inicial - QualityDoc PHP (Windows)
# =================================================================

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  Configuracion de QualityDoc PHP" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan

# 1. Verificar Docker
try {
    $null = docker info 2>&1
    if ($LASTEXITCODE -ne 0) { throw }
} catch {
    Write-Host "ERROR: Docker no esta ejecutandose." -ForegroundColor Red
    Read-Host "Presiona Enter para salir..."
    exit 1
}

# 2. Pedir credenciales para PostgreSQL
Write-Host "`nConfiguracion de PostgreSQL:" -ForegroundColor Green
$dbUser = Read-Host "Ingresa el USUARIO (Enter para 'postgres')"
if ([string]::IsNullOrWhiteSpace($dbUser)) { $dbUser = "postgres" }

$dbName = Read-Host "Ingresa el nombre de la BASE DE DATOS (Enter para 'qualitydoc')"
if ([string]::IsNullOrWhiteSpace($dbName)) { $dbName = "qualitydoc" }

$isValidPassword = $false
while (-not $isValidPassword) {
    Write-Host "`nIngresa una contrasena para PostgreSQL (minimo 6 caracteres):" -ForegroundColor Yellow
    $dbPassword = Read-Host -AsSecureString "Contrasena"
    $dbPasswordPlain = [System.Runtime.InteropServices.Marshal]::PtrToStringAuto([System.Runtime.InteropServices.Marshal]::SecureStringToBSTR($dbPassword))
    
    if ($dbPasswordPlain.Length -ge 6) {
        $isValidPassword = $true
    } else {
        Write-Host "ERROR: Contrasena demasiado corta." -ForegroundColor Red
    }
}

$apiLoginUri = Read-Host "`nIngresa la URL de la API de Login (Enter para 'http://localhost:5000')"
if ([string]::IsNullOrWhiteSpace($apiLoginUri)) { $apiLoginUri = "http://localhost:5000" }

# 3. Guardar en .env
Write-Host "`nGenerando archivo .env..."
Set-Content -Path ".env" -Value "DB_USER=$dbUser" -Encoding ascii
Add-Content -Path ".env" -Value "DB_PASSWORD=$dbPasswordPlain" -Encoding ascii
Add-Content -Path ".env" -Value "DB_NAME=$dbName" -Encoding ascii
Add-Content -Path ".env" -Value "API_LOGIN_URI=$apiLoginUri" -Encoding ascii

# 4. Limpiar e Iniciar Docker
Write-Host "`nLimpiando contenedores anteriores..." -ForegroundColor Cyan
docker compose down -v
Write-Host "Levantando entorno de PHP, Nginx y PostgreSQL..." -ForegroundColor Cyan
docker compose up -d --build

Write-Host "`n========================================" -ForegroundColor Green
Write-Host "  ¡Entorno configurado con exito!" -ForegroundColor Green
Write-Host "  Aplicacion PHP en: http://localhost:8080" -ForegroundColor Cyan
Write-Host "  PostgreSQL en el puerto: 5432" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Green

Read-Host "Presiona Enter para salir..."
