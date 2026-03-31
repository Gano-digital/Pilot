@echo off
REM Compilar Gano SFTP Manager a .exe con PyInstaller

echo.
echo ==========================================
echo  Gano Digital SFTP Manager - Build Script
echo ==========================================
echo.

REM Verificar que PyInstaller esté instalado
pip show pyinstaller >nul 2>&1
if %errorlevel% neq 0 (
    echo Installing PyInstaller...
    pip install pyinstaller
)

echo.
echo Building executable...
echo.

REM Compilar con PyInstaller
pyinstaller ^
    --onefile ^
    --windowed ^
    --icon=gano_icon.ico ^
    --name="Gano SFTP Manager" ^
    --distpath=./dist ^
    --buildpath=./build ^
    --specpath=./spec ^
    gano_sftp_manager.py

echo.
echo ==========================================
echo Build complete!
echo Executable: dist/Gano SFTP Manager.exe
echo ==========================================
echo.
pause
