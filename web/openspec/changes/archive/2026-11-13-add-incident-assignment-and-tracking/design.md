# Design

## Context

Tras el issue 1 existen `Incident` (con `status` y `requester_id`), los enums `IncidentStatus` e
`IncidentPriority`, `IncidentPolicy` y el recurso `IncidentResource` con páginas List, Create y View
(sin Edit). Ver `proposal.md` para la motivación y los specs para los requisitos.

## Goals / Non-Goals

**Goals:**
- Reglas de transición en un único sitio, comprobadas tanto por la interfaz como por el dominio.
- Cambio de estado e historial atómicos.

**Non-Goals:**
- Avisos por correo, plazos según prioridad, reparto automático, historial de asignaciones.

## Decisions

- **Transiciones en el enum**: `IncidentStatus::allowedTransitions(): array` y
  `canTransitionTo(self $to): bool`. Es la fuente única de la tabla de transiciones.
- **Lógica de dominio en el modelo**: `Incident::changeStatus(IncidentStatus $to, User $by)` valida la
  transición y la regla «en curso requiere técnico», y en una transacción actualiza el estado y crea el
  `IncidentStatusChange`. Si no es válida lanza `App\Exceptions\InvalidIncidentTransition` con un
  mensaje en español. `Incident::assignTo(User $technician)` lanza `InvalidArgumentException` si el
  usuario no es técnico. Alternativa descartada: una clase de servicio o acción; para dos métodos no
  aporta claridad en un proyecto de formación.
- **Opciones del formulario**: `Incident::availableStatusTransitions()` filtra las transiciones del enum
  quitando «en curso» si no hay técnico. La acción «Cambiar estado» solo ofrece esas opciones y, aun así,
  captura la excepción del dominio y muestra una notificación de error en español.
- **Esquema**: migración que añade `technician_id` (FK nullable a `users`) a `incidents`, y tabla
  `incident_status_changes` con `incident_id`, `user_id`, `from_status`, `to_status` y timestamps.
  La fecha del cambio es `created_at`.
- **Autorización** en `IncidentPolicy`:
  - `assign`: el usuario es administrador y la incidencia no está cerrada.
  - `changeStatus`: el usuario es administrador o el técnico asignado, y la incidencia tiene transiciones posibles.
  - `view` no cambia.
- **Interfaz** en `ViewIncident`: acciones de cabecera «Asignar» (Select de técnicos) y «Cambiar estado»
  (Select de transiciones posibles), visibles según la policy. El historial se muestra en la ficha con un
  `RepeatableEntry` sobre la relación `statusChanges`, ordenada por `created_at` y `id` descendente. La
  tabla y la ficha añaden «Técnico asignado».
- **Decisiones sobre las notas del issue**:
  - Una incidencia resuelta se puede reabrir (pasa a en curso); una cerrada no.
  - Solo un administrador asigna y reasigna, y los cambios de asignación no se registran en el historial.
  - Cambia el estado el técnico asignado o un administrador.
  - No se puede pasar a en curso sin técnico.

## Risks / Trade-offs

- [Un técnico no puede tomar una incidencia por sí mismo] → Aceptado; se puede añadir una acción
  «Asignármela» más adelante sin cambiar el modelo.
- [Reasignar no deja rastro] → Fuera de alcance según el issue; queda anotado.

## Migration Plan

Migraciones aditivas; las incidencias existentes quedan sin técnico y sin historial. Rollback con
`migrate:rollback`.
