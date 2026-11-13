# Proposal

## Why

Con el alta de incidencias, todas se quedan «abiertas» para siempre: nadie es responsable de ellas
ni puede hacerlas avanzar, y el solicitante no sabe qué se ha hecho (issue 2,
`docs/issues/02-asignacion-y-seguimiento.md`).

## What Changes

- Un administrador puede asignar una incidencia a un técnico, y reasignarla mientras no esté cerrada.
- El técnico asignado o un administrador pueden cambiar el estado, solo por transiciones válidas.
- Cada cambio de estado queda registrado (estado anterior, estado nuevo, quién y cuándo) y se
  muestra en la ficha, del más reciente al más antiguo.
- El listado y la ficha muestran el técnico asignado.
- La regla «sin edición» del issue 1 se precisa: título, descripción y prioridad siguen sin poder
  editarse, pero estado y técnico cambian mediante acciones específicas.

## Capabilities

### New Capabilities

- `incident-workflow`: asignación de incidencias a técnicos, ciclo de vida por estados con
  transiciones válidas e historial de cambios de estado.

### Modified Capabilities

- `incidents`: el listado añade la columna de técnico asignado y la regla de no edición pasa a
  permitir los cambios de estado y de asignación definidos en `incident-workflow`.

## Impact

- Migraciones nuevas: columna `technician_id` en `incidents` y tabla `incident_status_changes`.
- Modelo y factory nuevos `IncidentStatusChange`; lógica de transiciones en `IncidentStatus` e `Incident`.
- `IncidentPolicy` añade `assign` y `changeStatus`.
- Recurso de Filament: acciones «Asignar» y «Cambiar estado» en la ficha, historial y columna de técnico.
- Tests de Feature nuevos.
