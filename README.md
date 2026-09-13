# Passaporte de Implantes

Sistema web para registrar pacientes com dispositivos cardíacos implantáveis (marca-passo, CDI, ressincronizador e monitor de eventos).

Projeto final da disciplina, feito com **Laravel 12** e **PostgreSQL**.

## Requisitos

- PHP 8.2 ou superior, com as extensões `pdo_pgsql` e `pgsql` habilitadas
- Composer
- PostgreSQL
- Node.js 18 ou superior

## Instalação

```bash
composer install
cp .env.example .env
php artisan key:generate
```

Crie o banco `passaporte_implantes` no PostgreSQL e preencha `DB_USERNAME` e `DB_PASSWORD` no `.env`. Depois:

```bash
php artisan migrate
npm install
npm run build
php artisan serve
```
