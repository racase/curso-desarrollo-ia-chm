# Tasks

## 1. Modelo de datos

- [x] 1.1 Generar `IncidentComment` con `make:model -mf`, relaciones `incident`, `author` y `Incident::comments()` ordenada ascendente; verificar que `migrate:fresh --seed` funciona
- [x] 1.2 Añadir comentarios de ejemplo al `IncidentSeeder` y verificarlo con el test del seeder

## 2. Autorización

- [x] 2.1 Añadir `comment` a `IncidentPolicy` y verificarla con tests por rol y con una incidencia cerrada

## 3. Ficha de la incidencia

- [x] 3.1 Añadir la acción «Comentar» con validación en español (obligatorio, máximo 2000) y verificar con tests Livewire el alta y los errores
- [x] 3.2 Mostrar la sección «Comentarios» con autor, fecha y texto del más antiguo al más reciente, y verificar con tests de la ficha el orden y que el solicitante ajeno no accede

## 4. Cierre

- [x] 4.1 Ejecutar `vendor/bin/pint --test` y `php artisan test` en el contenedor y verificar que todo está en verde

## Workflow follow-up

- Archivar el cambio con `openspec-archive-change`.
