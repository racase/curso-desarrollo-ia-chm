# Proposal

## Why

InfraTicket solo ofrece acceso al panel y no permite registrar ni consultar
incidencias de mantenimiento. Esta capacidad es la base funcional de la que
dependen la asignación, los comentarios, los activos y los indicadores del
backlog.

## What Changes

- Incorporar incidencias con título, descripción, estado, prioridad y
  solicitante.
- Permitir que un solicitante registre una incidencia propia con estado inicial
  abierta y una prioridad elegida entre baja, media, alta y crítica.
- Mostrar un listado con filtros por estado y prioridad.
- Limitar a los solicitantes a sus propias incidencias y ofrecer a técnicos y
  administradores una vista completa.
- Mantener fuera de este cambio la edición posterior, la asignación, las
  transiciones de estado, los comentarios, los activos y los widgets.

## Capabilities

### New Capabilities

- `incident-intake`: Alta y consulta de incidencias, valores permitidos de estado
  y prioridad, filtros y visibilidad por rol.

### Modified Capabilities

- Ninguna.

## Impact

- Nuevo dominio de incidencias con persistencia, datos de prueba y valores
  cerrados para estado y prioridad.
- Nuevo recurso del panel para el alta, detalle y listado de incidencias.
- Autorización por rol y propiedad de la incidencia.
- Pruebas de funcionalidad para alta, consulta, filtros y restricciones de
  acceso.
