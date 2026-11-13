# Tasks

## 1. Modelo y reglas de dominio

- [x] 1.1 Añadir `allowedTransitions()` y `canTransitionTo()` a `IncidentStatus` y verificar la tabla de transiciones con un test unitario
- [x] 1.2 Crear la migración que añade `technician_id` a `incidents` y el modelo `IncidentStatusChange` con migración y factory (`make:model -mf`); verificar que `migrate:fresh --seed` funciona
- [x] 1.3 Añadir a `Incident` las relaciones `technician` y `statusChanges`, y los métodos `assignTo`, `changeStatus` y `availableStatusTransitions` con la excepción `InvalidIncidentTransition`; verificar con tests de Feature las transiciones válidas, las rechazadas, la regla de técnico y el registro del historial

## 2. Autorización

- [x] 2.1 Añadir `assign` y `changeStatus` a `IncidentPolicy` y verificar con tests por rol (admin, técnico asignado, otro técnico, solicitante)

## 3. Panel

- [x] 3.1 Añadir en `ViewIncident` las acciones «Asignar» y «Cambiar estado» con mensajes en español y verificar con tests Livewire su visibilidad y efecto, incluido el rechazo de «en curso» → «cerrada»
- [x] 3.2 Mostrar el técnico asignado en la tabla y la ficha, y el historial en la ficha del más reciente al más antiguo; verificar con tests
- [x] 3.3 Añadir al seeder incidencias de ejemplo asignadas y con historial, y verificarlo con el test del seeder

## 4. Cierre

- [x] 4.1 Ejecutar `vendor/bin/pint --test` y `php artisan test` en el contenedor y verificar que todo está en verde

## Workflow follow-up

- Verificar el cambio y archivarlo con `openspec-archive-change`.
