# Proposal

## Why

Una incidencia de mantenimiento siempre afecta a algo concreto en un sitio concreto, y hoy eso solo se
puede escribir a mano en la descripción. Sin sedes ni activos no se puede organizar el trabajo por
ubicación (issue 4, `docs/issues/04-sedes-y-activos.md`).

## What Changes

- Gestión de sedes desde el panel (alta, edición y listado), solo para administradores.
- Gestión de activos desde el panel (alta, edición y listado); cada activo pertenece a una sede. Solo administradores.
- **BREAKING (comportamiento)**: el alta de incidencias exige elegir un activo.
- El listado y la ficha de la incidencia muestran el activo y su sede, y el listado se puede filtrar por sede.
- Datos de ejemplo con Planta Valencia, Planta Sagunto y Almacén Paterna y sus activos.
- Las incidencias creadas antes de este cambio se conservan sin activo y se muestran como «Sin activo».

## Capabilities

### New Capabilities

- `sites-and-assets`: catálogo de sedes y activos mantenido por los administradores.

### Modified Capabilities

- `incidents`: el alta exige un activo; el listado muestra activo y sede y filtra por sede; la ficha muestra activo y sede.

## Impact

- Modelos `Site` y `Asset` con migraciones, factories, seeder, policies y recursos de Filament.
- Migración nueva que añade `asset_id` (nullable) a `incidents`.
- Formulario, tabla e infolist del recurso de incidencias, `IncidentSeeder` y tests existentes del alta.
