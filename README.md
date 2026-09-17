# Passaporte de Implantes

Sistema web para registrar pacientes com dispositivos cardíacos implantáveis (marca-passo, CDI, ressincronizador e monitor de eventos).

Projeto final da disciplina, feito com **Laravel 12** e **PostgreSQL**.

## Integrantes

- Sofia Scheidt Alves — [função/partes desenvolvidas]
- Pedro Forbeck da Matta Oliveira — [função/partes desenvolvidas]

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
php artisan migrate:fresh --seed
npm install
npm run build
php artisan serve
```

O comando `php artisan migrate:fresh --seed` recria o banco do zero **e** popula com os dados e usuários de teste listados abaixo.

## Usuários para teste

Todos usam a senha `password`.

| Perfil        | E-mail                    |
| ------------- | ------------------------- |
| Administrador | admin@passaporte.test     |
| Médica        | medica@passaporte.test    |
| Médico        | medico@passaporte.test    |
| Paciente      | paciente@passaporte.test  |

## Decisões de domínio

- Pacientes não são apagáveis: o histórico clínico (dispositivos e acompanhamentos) deve ser preservado para fins médicos e legais.
- Dispositivos e acompanhamentos podem ser removidos apenas por administradores.
- O paciente não consegue vincular o próprio passaporte pela conta: o vínculo entre a conta de acesso e o registro do paciente é feito pelo médico ou administrador (limitação conhecida, para evitar apropriação indevida de dados clínicos).
