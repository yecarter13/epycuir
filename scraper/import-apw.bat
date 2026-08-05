@echo off
REM AutoPartsWay Import Helper for Windows
REM Usage: import-apw.bat <url> [output-file]

set SCRAPER_DIR=%~dp0
set PROJECT_DIR=%SCRAPER_DIR%..
set OUTPUT_FILE=%PROJECT_DIR%\storage\app\autopartsway-products.json

if "%1"=="" (
  echo AutoPartsWay Import Helper
  echo.
  echo Usage: %0 ^<url^> [output-file]
  echo.
  echo Examples:
  echo   %0 "https://autopartsway.com/category-go.php?cat=Brake^&filter%%5Bmake_description%%5D=Fiat"
  echo   %0 "https://autopartsway.com/Fiat_parts.html"
  echo   %0 "https://autopartsway.com/category-go.php?cat=Engine"
  echo.
  echo Quick import of a JSON file you already have:
  echo   php "%PROJECT_DIR%\artisan" products:import "%OUTPUT_FILE%" --download-images --category="Brakes"
  exit /b 1
)

if not "%2"=="" set OUTPUT_FILE=%2

echo Step 1: Installing Puppeteer (if needed)
cd /d "%SCRAPER_DIR%"
call npm install 2>nul
if %ERRORLEVEL% neq 0 (
  echo npm install failed. Make sure Node.js is installed.
  exit /b 1
)

echo Step 2: Scraping AutoPartsWay...
node autopartsway-scraper.js "%1" "%OUTPUT_FILE%"
if %ERRORLEVEL% neq 0 (
  echo Scraping failed.
  exit /b 1
)

echo.
echo Step 3: Importing into your site...
echo Select the category for these products by editing the command below, then run:
echo   php "%PROJECT_DIR%\artisan" products:import "%OUTPUT_FILE%" --download-images --category="YourCategory"
echo.
echo Or run now with a default category:
set /p CATEGORY="Enter category name (or leave blank for none): "
if not "%CATEGORY%"=="" (
  php "%PROJECT_DIR%\artisan" products:import "%OUTPUT_FILE%" --download-images --category="%CATEGORY%"
)

echo.
echo Done!
