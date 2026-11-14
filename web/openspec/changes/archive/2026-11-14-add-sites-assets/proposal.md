# Change: Add sites and assets

## Why

Las incidencias describen el problema, pero no identifican de forma estructurada
el elemento ni la sede afectados. Añadir este contexto reducirá ambigüedades para
solicitantes, técnicos y administradores.

## What Changes

- Añadir gestión administrativa de sedes con nombre único y dirección opcional.
- Añadir gestión administrativa de activos con código único, nombre y una sede
  activa obligatoria.
- Restringir el mantenimiento de sedes y activos a administradores; técnicos y
  solicitantes solo los consultarán en el contexto permitido de una incidencia.
- Eliminar sedes y activos de forma lógica, conservar su contexto histórico e
  impedir eliminar una sede mientras tenga activos activos.
- Exigir un activo activo al registrar nuevas incidencias y mostrar el activo,
  su código y su sede en el listado y detalle del caso.
- Conservar las incidencias existentes sin activo durante la migración, sin
  permitir nuevas altas incompletas.

## Capabilities

### New Capabilities

- asset-management: catálogo administrativo de sedes y activos, sus relaciones,
  reglas de acceso, validación y eliminación lógica.

### Modified Capabilities

- incident-intake: el alta de nuevas incidencias requiere un activo activo y la
  consulta muestra el activo y la sede asociados preservando el histórico.

## Impact

- Nuevos modelos, migraciones, factories, seeders, policies y recursos Filament
  para sedes y activos.
- Nueva relación desde incidencias hacia activos y actualización de sus
  formularios, listados, detalle y datos de ejemplo.
- Nuevos tests de Feature para autorización, CRUD, integridad, alta de
  incidencias y trazabilidad tras eliminaciones lógicas.
- Sin dependencias nuevas de Composer o npm.
