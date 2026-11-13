# Proposal

## Why

InfraTicket solo ofrece hoy el acceso al panel y los usuarios de prueba: nadie puede
registrar una incidencia de mantenimiento ni el equipo técnico puede consultarlas. Es la
base sobre la que se apoyan el resto de issues del backlog (issue 1, `docs/issues/01-alta-de-incidencias.md`).

## What Changes

- Nuevo concepto de incidencia con título, descripción, prioridad, estado, solicitante y fecha de alta.
- Prioridades cerradas: baja, media, alta y crítica. Estados cerrados: abierta, en curso,
  resuelta y cerrada. Toda incidencia nueva nace «abierta».
- Formulario de alta en el panel: la persona que da de alta queda como solicitante.
- Listado de incidencias con título, estado, prioridad, solicitante y fecha de alta, y
  filtros por estado y por prioridad.
- Ficha de consulta de la incidencia.
- Visibilidad por rol: un solicitante solo ve sus incidencias; técnicos y administradores ven todas.
- Datos de ejemplo con incidencias de los solicitantes de prueba.

## Capabilities

### New Capabilities

- `incidents`: registro y consulta de incidencias de mantenimiento, sus prioridades y
  estados, y quién puede verlas.

### Modified Capabilities

Ninguna.

## Impact

- Código nuevo: enums de prioridad y estado, modelo `Incident` con migración, factory y
  seeder, policy de autorización y recurso de Filament.
- `DatabaseSeeder` añade incidencias de ejemplo.
- Tests de Feature nuevos. Sin dependencias nuevas.
