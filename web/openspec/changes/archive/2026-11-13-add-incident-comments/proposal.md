# Proposal

## Why

La descripción inicial de una incidencia casi nunca basta: el técnico necesita preguntar y el
solicitante aclarar. Hoy esa conversación ocurre fuera de la aplicación y se pierde (issue 3,
`docs/issues/03-comentarios-en-la-incidencia.md`).

## What Changes

- Añadir comentarios de texto desde la ficha de una incidencia.
- Mostrar los comentarios en la ficha en orden cronológico, con autor y fecha y hora.
- Comenta y lee los comentarios quien puede ver la incidencia: su solicitante, los técnicos y los
  administradores.
- No se admiten comentarios nuevos en incidencias cerradas, y un comentario tiene como máximo 2000 caracteres.

## Capabilities

### New Capabilities

- `incident-comments`: conversación por comentarios en la ficha de una incidencia.

### Modified Capabilities

Ninguna.

## Impact

- Modelo `IncidentComment` con migración y factory; relación `comments` en `Incident`.
- `IncidentPolicy` añade `comment`.
- Ficha de la incidencia: acción «Comentar» y sección de comentarios.
- Seeder con comentarios de ejemplo y tests de Feature nuevos.
