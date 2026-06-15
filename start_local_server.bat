@echo off
echo Starting VoyageQuest local environment...

:: Clear stale pid files
if exist C:\xampp\mysql\data\mysql.pid (
    del C:\xampp\mysql\data\mysql.pid
)
if exist C:\xampp\apache\logs\httpd.pid (
    del C:\xampp\apache\logs\httpd.pid
)

:: Start MySQL and Apache in background
start "" /B "C:\xampp\mysql\bin\mysqld.exe" --defaults-file="C:\xampp\mysql\bin\my.ini" --standalone
start "" /B "C:\xampp\apache\bin\httpd.exe"

echo Waiting for services to initialize...
timeout /t 3 /nobreak > nul

echo Opening VoyageQuest in browser...
start http://localhost/travel/index.php
