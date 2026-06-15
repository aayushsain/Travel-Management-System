@echo off
echo Stopping VoyageQuest local environment...
taskkill /IM httpd.exe /F
taskkill /IM mysqld.exe /F
echo Services stopped successfully.
pause
