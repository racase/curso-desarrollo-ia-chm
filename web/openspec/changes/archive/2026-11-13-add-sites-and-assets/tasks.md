# Tasks

## 1. Modelo de datos

- [x] 1.1 Generar `Site` y `Asset` con `make:model -mf` (relaciones `site`, `assets`, unicidad) y la migración que añade `asset_id` nullable a `incidents` con la relación `Incident::asset`; verificar que `migrate:fresh` funciona
- [x] 1.2 Crear `SiteSeeder` con Planta Valencia, Planta Sagunto y Almacén Paterna y sus activos, ejecutarlo antes de `IncidentSeeder` y asignar activo a las incidencias de ejemplo; verificarlo con el test del seeder

## 2. Catálogo de sedes y activos

- [x] 2.1 Crear `SitePolicy` y `AssetPolicy` (solo administradores, sin borrado) y verificarlas con tests por rol
- [x] 2.2 Generar `SiteResource` y `AssetResource` (List, Create, Edit) en español, sin acciones de borrado; verificar con tests el alta de sede y activo, la unicidad, el activo sin sede y que técnico y solicitante no acceden

## 3. Incidencias con activo

- [x] 3.1 Añadir al alta el selector de activo agrupado por sede y obligatorio; actualizar los tests existentes del alta y verificar con un test el error en español sin activo
- [x] 3.2 Mostrar activo y sede en la tabla y la ficha («Sin activo» si no tiene) y añadir el filtro por sede; verificar con tests la ficha, el filtro y una incidencia antigua sin activo

## 4. Cierre

- [x] 4.1 Ejecutar `vendor/bin/pint --test` y `php artisan test` en el contenedor y verificar que todo está en verde

## Workflow follow-up

- Archivar el cambio con `openspec-archive-change`.
