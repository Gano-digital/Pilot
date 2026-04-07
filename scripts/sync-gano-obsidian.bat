@echo off
REM Gano Digital - Obsidian Synchronization Script
REM Simple batch file using curl (built into Windows 10+)

setlocal enabledelayedexpansion

set API_KEY=1d3446a85589777fb01d0fae164ae8b458400ea58af0ab700a38d634eaf3c946
set API_URL=https://localhost:27124
set TIMESTAMP=%date:~-4,4%-%date:~-10,2%-%date:~-7,2% %time:~0,2%:%time:~3,2%

echo.
echo ============================================
echo  Gano Digital - Obsidian Sync
echo  %TIMESTAMP%
echo ============================================
echo.

REM Test connection
echo Testing connection to Obsidian...
curl -s -k -H "Authorization: Bearer %API_KEY%" "%API_URL%/vault/" > nul 2>&1

if errorlevel 1 (
    echo ERROR: Could not connect to Obsidian Local REST API
    echo.
    echo Make sure:
    echo  1. Obsidian is open
    echo  2. Local REST API plugin is installed
    echo  3. HTTPS server is running on port 27124
    echo.
    pause
    exit /b 1
)

echo [OK] Connected!
echo.

REM Create status content
set STATUS_CONTENT=# Status - Obsidian Sync Active^^n^^nLast sync: %TIMESTAMP% UTC^^nSystem: Online^^nConnection: Obsidian Local REST API^^n^^n## Phase 4 Metrics^^n- Completion: 15%%^^n- PFID Mappings: 0/5^^n- Blockers: 1^^n- Go-Live: Apr 20, 2026^^n^^n---^^nSynced via REST API

REM Create JSON payload (simple approach)
echo Creating status file...

curl -s -k -X POST ^
  -H "Authorization: Bearer %API_KEY%" ^
  -H "Content-Type: application/json" ^
  -d "{\""path\"":\"\"memory/constellation/STATUS-LIVE.md\"\",\""content\"":\"\"# Status - Obsidian Sync Active\n\nLast sync: %TIMESTAMP% UTC\n\n## Phase 4 Progress\n- Completion: 15%%\n- PFID: 0/5\n- Blockers: 1\n- Go-Live: Apr 20, 2026\n\n---\nSynced via Obsidian Local REST API\"\",\""overwrite\"":true}" ^
  "%API_URL%/vault/create" > nul 2>&1

if errorlevel 1 (
    echo [!] Error writing file
) else (
    echo [OK] Status file updated!
)

echo.
echo ============================================
echo  Sync Complete!
echo ============================================
echo.
echo Your Obsidian vault has been synchronized.
echo Check: memory/constellation/STATUS-LIVE.md
echo.

REM Optional: Keep window open for review
if "%1%"=="--pause" pause
