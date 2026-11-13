# Design

## Context

El proyecto tiene `User` con `role` (`App\Enums\UserRole`), un panel Filament 5 en `/admin` que
descubre recursos en `app/Filament/Resources` y un `DatabaseSeeder` con cinco usuarios de prueba.
No hay todavía ningún modelo de negocio ni policies. Ver `proposal.md` para la motivación.

## Goals / Non-Goals

**Goals:**
- Modelo de incidencia convencional de Laravel, con enums, policy y recurso de Filament generados con Artisan.
- Visibilidad por rol aplicada en un solo sitio, de forma que sirva también al listado y a futuros widgets.

**Non-Goals:**
- Asignación, cambios de estado e historial (issue 2), comentarios (issue 3), sedes y activos (issue 4).
- Edición y borrado de incidencias.

## Decisions

- **Enums `IncidentPriority` e `IncidentStatus`** en `app/Enums`, respaldados por string
  (`low`, `medium`, `high`, `critical` / `open`, `in_progress`, `resolved`, `closed`), con
  `HasLabel` y `HasColor` de Filament para mostrar etiquetas en español y badges con color.
- **Tabla `incidents`**: `id`, `title` (string), `description` (text, nullable), `priority`,
  `status` (string con valor por defecto `open`), `requester_id` (FK a `users`), timestamps.
  La fecha de alta es `created_at`. Se usan strings y no enums de base de datos para que SQLite
  y PostgreSQL se comporten igual.
- **El solicitante y el estado no están en el formulario.** Se fijan en `CreateIncident::mutateFormDataBeforeCreate`
  (`requester_id = auth()->id()`, `status = open`). Alternativa descartada: campos ocultos, que el
  cliente podría manipular.
- **Visibilidad**: `IncidentPolicy` decide `viewAny`, `view` y `create`; `update` y `delete`
  devuelven `false`. El listado se restringe con un scope `Incident::visibleTo(User $user)` aplicado en
  `IncidentResource::getEloquentQuery()`, así el mismo scope sirve para la ficha (404/403) y para el
  widget del issue 5. La policy `view` comprueba la misma regla para denegar el acceso directo por URL.
- **Recurso de Filament** con páginas `List`, `Create` y `View` (sin `Edit`). Etiquetas del recurso
  en español: «Incidencia» / «Incidencias».
- **Decisiones sobre las notas del issue**:
  - Técnicos y administradores pueden dar de alta, pero siempre en su propio nombre (no en nombre de otra persona).
  - Nadie edita título, descripción ni prioridad tras el alta en este cambio; se revisará si hace falta.
  - Prioridad por defecto en el formulario: «media».

## Risks / Trade-offs

- [El acceso directo a una ficha ajena puede dar 404 en lugar de 403 por el scope del recurso] →
  Ambos deniegan el acceso; los tests comprueban que la respuesta no es 200 y que la policy niega `view`.
- [Sin edición, un error de tecleo no se puede corregir] → Aceptado para este alcance; queda anotado.

## Migration Plan

Migración nueva aditiva (`create_incidents_table`). Rollback con `migrate:rollback`.
