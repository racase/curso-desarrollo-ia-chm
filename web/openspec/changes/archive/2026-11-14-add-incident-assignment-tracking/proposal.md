# Proposal

## Why

Las incidencias ya pueden registrarse y consultarse, pero todavía no tienen una
persona técnica responsable ni una evolución controlada. La asignación, las
transiciones válidas y el historial son necesarios para coordinar el trabajo y
comprender el progreso de cada caso.

## What Changes

- Permitir que los administradores asignen o reasignen una incidencia a un
  usuario con rol técnico.
- Permitir que el técnico asignado y los administradores avancen el estado por
  la secuencia abierta, en curso, resuelta y cerrada.
- Rechazar saltos, retrocesos y el cierre de incidencias no resueltas.
- Registrar cada cambio de estado con estado anterior, estado nuevo, autor y
  fecha, y mostrar el historial en la ficha.
- Mantener el resto de datos de la incidencia sin edición y conservar las reglas
  de visibilidad existentes.

## Capabilities

### New Capabilities

- `incident-tracking`: Asignación a técnicos, transiciones de estado e historial
  visible de cambios.

### Modified Capabilities

- `incident-intake`: Sustituir la prohibición absoluta de edición por cambios
  controlados de asignación y estado, manteniendo inmutables los datos del alta.

## Impact

- Nuevas relaciones de técnico asignado e historial de estados en el dominio de
  incidencias, con migraciones y factories asociadas.
- Nuevas acciones autorizadas en la ficha de Filament para asignar y avanzar el
  estado, sin introducir una página de edición general.
- Ampliación de la policy de incidencias y de las pruebas Feature y Livewire para
  permisos, transiciones, trazabilidad y visibilidad.
