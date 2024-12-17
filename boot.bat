@echo off

echo Creating 'temp' directory...
mkdir temp

echo Creating 'log' directory...
mkdir log

composer install --ignore-platform-reqs
if %errorlevel% neq 0 (
    echo Composer install failed. Exiting...
    exit /b
)

echo All tasks completed successfully.
pause
