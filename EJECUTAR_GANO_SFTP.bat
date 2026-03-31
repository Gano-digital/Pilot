@echo off
REM Gano Digital SFTP Manager Launcher
REM Este archivo inicia la aplicación CON consola para ver errores

cd /d "%~dp0"
python gano_sftp_manager.py

REM pythonw = sin ventana de consola (recomendado para producción)
REM python = con ventana de consola (para debuggear) - ACTUALMENTE ACTIVO
