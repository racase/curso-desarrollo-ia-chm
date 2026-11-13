# Tasks

## 1. Modelo de datos

- [x] 1.1 Crear los enums `IncidentPriority` e `IncidentStatus` con etiquetas en español y colores, y verificarlos con un test unitario de etiquetas
- [x] 1.2 Generar `Incident` con `make:model -mf` (migración, factory), relación `requester` en `Incident` y `incidents` en `User`, casts a enums y scope `visibleTo`; verificar que `migrate:fresh` funciona
- [x] 1.3 Añadir incidencias de ejemplo en un `IncidentSeeder` llamado desde `DatabaseSeeder` y verificar con un test que el seeder crea incidencias

## 2. Autorización

- [x] 2.1 Crear `IncidentPolicy` (viewAny, view, create; update y delete a false) y verificarla con tests de Feature por rol

## 3. Panel

- [x] 3.1 Generar el recurso de Filament `IncidentResource` con páginas List, Create y View, formulario con título, descripción y prioridad (media por defecto) y verificar con tests que el alta fija solicitante y estado «abierta»
- [x] 3.2 Configurar la tabla (columnas, orden por fecha descendente, filtros por estado y prioridad) y restringir la consulta con `visibleTo`; verificar con tests de listado y filtros
- [x] 3.3 Verificar con tests la validación en español del alta sin título o sin prioridad y la denegación de la ficha ajena

## 4. Cierre

- [x] 4.1 Ejecutar `vendor/bin/pint --test` y `php artisan test` en el contenedor y verificar que todo está en verde

## Workflow follow-up

- Verificar el cambio con `openspec-verify-change` y archivarlo con `openspec-archive-change`.
