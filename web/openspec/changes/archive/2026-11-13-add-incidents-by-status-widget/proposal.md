# Proposal

## Why

Al entrar al panel no hay ninguna visión de conjunto: para saber cuánto trabajo hay pendiente hay que
abrir el listado y filtrar estado por estado (issue 5, `docs/issues/05-widget-incidencias-por-estado.md`).

## What Changes

- Resumen en la página de inicio del panel con el número de incidencias en cada uno de los cuatro estados,
  incluidos los que están a cero.
- El resumen respeta la visibilidad por rol: técnicos y administradores cuentan todas, un solicitante solo las suyas.
- Cada cifra enlaza al listado de incidencias filtrado por ese estado.

## Capabilities

### New Capabilities

- `incident-dashboard`: resumen de incidencias en la página de inicio del panel.

### Modified Capabilities

Ninguna.

## Impact

- Widget nuevo de Filament en `app/Filament/Widgets`, descubierto automáticamente por el panel.
- Tests de Feature nuevos. Sin cambios de esquema.
