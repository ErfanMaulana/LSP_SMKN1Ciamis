@echo off
setlocal

cd /d "%~dp0"

echo Menjalankan LSP_SMKN1Ciamis di http://localhost:9999/
echo Tekan Ctrl+C untuk menghentikan server.
php artisan serve --host=127.0.0.1 --port=9999
