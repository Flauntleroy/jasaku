@echo off
echo Creating database 'sign' and importing schema...

mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS sign CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;"

mysql -u root -p sign < sign.sql

echo Database setup completed!
echo You can now access: http://localhost/jasa/index.php/setup/seed_data
pause