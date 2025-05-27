<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

<!-- Freelance Time Tracker API -->

A Laravel 12 API to allow freelancers to track time across clients and projects.

1. Clone the repo:

```bash
git clone https://github.com/Rojifa-Akhter/Freelance-Time-Tracker-API.git

cd freelance-time-tracker

composer install

cp .env.example .env

php artisan key:generate

php artisan migrate --seed

php artisan serve


# Database Structure

# users
- id (primary key)
- name (string)
- role (enum: freelancer, client, admin)
- email (string, unique)
- password (string)
- timestamps (created_at, updated_at)

# clients
- id (primary key)
- user_id (foreign key to users.id)
- name (string)
- email (string)
- contact_person (string)
- timestamps

# projects
- id (primary key)
- client_id (foreign key to clients.id)
- title (string)
- description (text)
- status (enum: active, completed)
- deadline (date)
- timestamps

# time_logs
- id (primary key)
- project_id (foreign key to projects.id)
- start_time (datetime)
- end_time (datetime)
- description (text)
- hours (float)
- tag (string)
- timestamps

