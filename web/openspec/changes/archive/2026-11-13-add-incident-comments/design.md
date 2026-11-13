# Design

## Context

La ficha de la incidencia (`ViewIncident`) ya tiene acciones de cabecera («Asignar», «Cambiar estado»)
y un infolist con secciones; el historial de estados se muestra con un `RepeatableEntry`. La visibilidad
la deciden `IncidentPolicy::view` y el scope `visibleTo` del recurso. Ver `proposal.md`.

## Goals / Non-Goals

**Goals:**
- Reutilizar el patrón de la ficha (acción de cabecera + sección del infolist) en lugar de introducir otro.

**Non-Goals:**
- Editar o borrar comentarios, adjuntos, comentarios internos, menciones y avisos.

## Decisions

- **Tabla `incident_comments`**: `incident_id` (FK con borrado en cascada), `user_id` (FK), `body` (text),
  timestamps. Modelo `IncidentComment` con relaciones `incident` y `author` (`user_id`).
- **Relación ordenada**: `Incident::comments()` se ordena por `created_at` y `id` ascendentes.
- **Autorización**: `IncidentPolicy::comment(User, Incident)` devuelve `view($user, $incident)` y además
  exige que la incidencia no esté cerrada. Quien no ve la incidencia ni siquiera llega a la ficha (404 por
  el scope del recurso), y la acción no se muestra si la policy lo niega.
- **Interfaz**: acción de cabecera «Comentar» con un `Textarea` (`required`, `maxLength(2000)`), y una sección
  «Comentarios» en el infolist con un `RepeatableEntry` de autor, fecha y texto. Alternativa descartada: un
  RelationManager, que en páginas de vista es de solo lectura por defecto y añade una tabla con paginación
  que no encaja con una conversación.
- **Decisiones sobre las notas del issue**: no se comenta en incidencias cerradas y el límite es de 2000 caracteres.

## Risks / Trade-offs

- [La acción en un modal es menos directa que un formulario al pie de la conversación] → Aceptado por
  sencillez y coherencia con el resto de acciones de la ficha.
