# AGENTS.md — InfraTicket (web/)

Gestor de incidencias de mantenimiento en Laravel 13 + Filament 5 + PostgreSQL 16.
Es un proyecto de formación: prioriza código claro, convencional y fácil de revisar.

## Mapa

- `app/Models/`: modelos Eloquent. `User` tiene un `role` (`App\Enums\UserRole`).
- `app/Enums/`: enumerados respaldados por string, con etiqueta en español (`getLabel()`).
- `app/Providers/Filament/AdminPanelProvider.php`: configuración del panel `/admin`.
- `app/Filament/`: recursos, páginas y widgets de Filament (se descubren automáticamente).
- `database/migrations`, `database/factories`, `database/seeders`.
- `tests/Feature`, `tests/Unit`: PHPUnit.
- `openspec/`: especificaciones y cambios (cuando exista).

## Comandos

PHP y Composer no están instalados en el equipo: viven en el contenedor `app`.
Ejecuta siempre los comandos de PHP con el prefijo `docker compose exec app`.

- Arrancar: `docker compose up -d` (aplicación en http://localhost:8000/admin)
- Tests: `docker compose exec app php artisan test`
- Estilo: `docker compose exec app vendor/bin/pint`
- Artisan: `docker compose exec app php artisan <comando>`
- Recrear datos: `docker compose exec app php artisan migrate:fresh --seed`
- Log del servidor: `docker compose logs app`

## Convenciones

- Código, nombres de clases, tablas, columnas y commits en inglés.
- Todo texto que ve el usuario (etiquetas, mensajes, datos de ejemplo) en español.
- Usa los generadores de Artisan (`make:model -mf`, `make:filament-resource`) dentro del contenedor.
- Cada modelo nuevo lleva migración, factory y, si aporta datos de ejemplo, seeder.
- Estados y tipos cerrados se modelan como enums en `app/Enums`, no como strings sueltos.
- La autorización va en policies de Laravel, no en condiciones repartidas por los recursos.
- Los cambios de esquema son siempre migraciones nuevas; no edites migraciones existentes.
- Toda funcionalidad nueva lleva tests de Feature. Los tests usan SQLite en memoria y
  `RefreshDatabase`.
- No añadas paquetes de Composer ni de npm sin preguntarlo antes.

## No tocar

- `.env` (usa `.env.example` como referencia).
- `vendor/`, `storage/`, `bootstrap/cache/`, `public/css/filament`, `public/js/filament`.
- La configuración de autenticación del panel, salvo que la tarea lo pida.
- Nada fuera de `web/`.

## Terminado significa

- `docker compose exec app vendor/bin/pint --test` sin cambios pendientes.
- `docker compose exec app php artisan test` en verde.
- El cambio cumple los criterios de aceptación de su especificación.
