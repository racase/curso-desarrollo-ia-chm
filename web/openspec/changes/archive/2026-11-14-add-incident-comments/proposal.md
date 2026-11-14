# Proposal

## Why

La descripción inicial no siempre contiene toda la información necesaria para
resolver una incidencia. Incorporar la conversación en la propia ficha evita
perder contexto y permite reconstruir con claridad quién aportó cada dato y
cuándo lo hizo.

## What Changes

- Permitir que cualquier usuario con acceso a una incidencia publique
  comentarios desde su ficha, con independencia del estado del caso.
- Mostrar los comentarios en orden cronológico, identificando autor y fecha.
- Aplicar a la consulta y publicación de comentarios las mismas restricciones
  de acceso que a la incidencia.
- Mantener los comentarios publicados como registros inmutables, sin acciones
  de edición o eliminación.

## Capabilities

### New Capabilities

- `incident-comments`: Conversación cronológica vinculada a una incidencia, con
  autoría, fecha y autorización heredada del acceso al caso.

### Modified Capabilities

Ninguna.

## Impact

- Nuevo modelo de comentario con migración, factory y relaciones con incidencia
  y usuario.
- Nueva autorización específica para publicar comentarios sin modificar las
  reglas existentes de visibilidad de incidencias.
- Ampliación de la ficha de Filament con el listado cronológico y el formulario
  de publicación.
- Nuevas pruebas Feature y Livewire de persistencia, autorización, validación,
  orden y ausencia de edición o eliminación.
