# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is **MiPortal 2.0**, a Laravel 8 web application with PHP 7.3+/8.0+ that appears to be an employee management portal with modules for inventory management, maintenance scheduling, reports, and visits. The application uses Laravel Mix for asset compilation and includes Bootstrap 5 for frontend styling.

## Development Commands

### Backend (PHP/Laravel)
- **Install dependencies**: `composer install`
- **Run tests**: `vendor/bin/phpunit` or `php artisan test`
- **Start development server**: `php artisan serve`
- **Run migrations**: `php artisan migrate`
- **Seed database**: `php artisan db:seed`
- **Clear cache**: `php artisan cache:clear`
- **Generate application key**: `php artisan key:generate`

### Frontend (JavaScript/CSS)
- **Install dependencies**: `npm install`
- **Development build**: `npm run dev` or `npm run development`
- **Watch for changes**: `npm run watch`
- **Hot reload**: `npm run hot`
- **Production build**: `npm run prod` or `npm run production`

### Testing
- **Run all tests**: `vendor/bin/phpunit`
- **Run specific test suite**: `vendor/bin/phpunit --testsuite=Feature` or `vendor/bin/phpunit --testsuite=Unit`
- **Run single test**: `vendor/bin/phpunit tests/Feature/ExampleTest.php`

## Architecture & Structure

### Application Modules
The application is organized into several functional modules under `app/Http/Controllers/`:

- **Main/**: Core functionality (Users, Roles)
- **Malla/**: Grid/schedule management functionality
- **Inventario/**: Inventory management
- **Reporte/**: Reporting system
- **Visita/**: Visit management

### Key Models
The application has a rich domain model with 40+ entities including:
- **Core entities**: User, Rol, empleado (Employee)
- **Inventory**: equipo (Equipment), hardwares, softwares, mantenimiento (Maintenance)
- **Organizational**: departamento, cargo, cliente, unidad_negocio
- **Scheduling**: jornada, hora, evento, campana
- **Assignment models**: Various *_asignado models for equipment/software/hardware assignments

### Authentication & Authorization
- Uses Laravel's built-in authentication system
- Custom role-based permissions with `spatie/laravel-permission` package
- Routes protected by authentication middleware

### Database
- Uses Eloquent ORM with comprehensive migrations
- Database schema includes seeders for initial data
- SQL dump file included: `contactacom_APP_MI_PORTAL (Sin datos).sql`

### Frontend Architecture
- **Views**: Located in `resources/views/`
- **Assets**: Compiled via Laravel Mix from `resources/js/` and `resources/sass/`
- **Styling**: Bootstrap 5.1.3 with custom SCSS
- **JavaScript**: Axios for HTTP requests, Laravel Mix for bundling

### Key Dependencies
- **PDF Generation**: `barryvdh/laravel-dompdf`
- **Excel Processing**: `phpoffice/phpspreadsheet`
- **Forms**: `laravelcollective/html`
- **API**: Laravel Sanctum for API authentication
- **CORS**: `fruitcake/laravel-cors`

### Route Structure
Routes are well-organized with clear patterns:
- Resource routes for CRUD operations
- Nested routes for related entities
- RESTful API endpoints in `routes/api.php`
- Clear naming conventions for route names

### Code Style
- Follows Laravel coding standards
- EditorConfig configured for 4-space indentation
- StyleCI integration with Laravel preset for PHP 8
- PSR-4 autoloading for application classes

## Development Notes

- Environment configuration required via `.env` file (copy from `.env.example`)
- Database connection must be configured before running migrations
- The application appears to be a comprehensive employee and resource management system
- Heavy use of Eloquent relationships between models
- Multi-language support configured in `resources/lang/`