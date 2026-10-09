#!/usr/bin/env bash
set -e

echo "== ESTADO DE GIT =="
git status --short

echo "== TESTS =="
php artisan test

echo "== LARAVEL PINT =="
./vendor/bin/pint --test

echo "== RESUMEN DE CAMBIOS =="
git diff --stat
