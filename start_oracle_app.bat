@echo off
setlocal
set "PATH=C:\oracle\instantclient_19_28;C:\php74;%PATH%"
cd /d "%~dp0"
start "" http://localhost:8000/
C:\php74\php.exe -S localhost:8000 router.php
