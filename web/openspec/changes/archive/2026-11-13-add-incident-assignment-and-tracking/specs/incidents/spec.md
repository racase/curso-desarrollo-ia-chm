# Spec Delta

## MODIFIED Requirements

### Requirement: Listado de incidencias
El panel SHALL ofrecer un listado de incidencias con título, estado, prioridad, solicitante, técnico
asignado y fecha de alta, ordenado de la más reciente a la más antigua.

#### Scenario: Columnas del listado
- **WHEN** un técnico abre el listado de incidencias
- **THEN** cada fila muestra título, estado, prioridad, solicitante, técnico asignado y fecha de alta

### Requirement: Sin edición ni borrado
El título, la descripción y la prioridad de una incidencia MUST NOT poder editarse desde el panel, y
las incidencias MUST NOT poder borrarse. El estado y el técnico asignado solo cambian mediante las
acciones definidas en la capacidad `incident-workflow`.

#### Scenario: No hay acción de borrar
- **WHEN** cualquier usuario consulta el listado o la ficha de una incidencia
- **THEN** no se ofrece ninguna acción para editarla ni borrarla
