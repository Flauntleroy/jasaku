@echo off
echo Setting up database for Jasa/Bonus Digital Signature System...
echo.

echo Please make sure Laragon/XAMPP MySQL is running...
echo.

echo Creating database and importing data...
mysql -u root < setup_complete.sql

if %errorlevel% == 0 (
    echo.
    echo ✓ Database setup completed successfully!
    echo.
    echo You can now access the application:
    echo - URL: http://localhost/jasa/
    echo - Admin Login: admin / admin123
    echo - Pegawai Login: johndoe / pegawai123
    echo.
) else (
    echo.
    echo ✗ Database setup failed!
    echo Please check if MySQL is running and try again.
    echo.
)

pause