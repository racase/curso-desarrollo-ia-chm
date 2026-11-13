# incidents Specification

## Purpose
Permite registrar incidencias de mantenimiento y consultarlas en el panel, respetando qué
incidencias puede ver cada rol.

## Requirements

### Requirement: Alta de incidencias
Cualquier usuario autenticado SHALL poder dar de alta una incidencia indicando título,
descripción, prioridad y el activo afectado. La incidencia MUST quedar en estado «abierta» y con el
usuario que la da de alta como solicitante. El formulario MUST proponer la prioridad «media» por defecto.
El activo no se puede cambiar una vez creada la incidencia.

#### Scenario: Una solicitante da de alta una incidencia
- **WHEN** Sara Solicitante da de alta una incidencia con título, descripción, prioridad y activo
- **THEN** la incidencia aparece en su listado en estado «abierta» y con Sara como solicitante

#### Scenario: El solicitante no se puede elegir
- **WHEN** un técnico o un administrador da de alta una incidencia
- **THEN** la incidencia queda con esa misma persona como solicitante

#### Scenario: Prioridad por defecto
- **WHEN** un usuario abre el formulario de alta
- **THEN** la prioridad aparece preseleccionada como «media»

### Requirement: Validación del alta
El alta MUST rechazarse si falta el título, la prioridad o el activo, mostrando un mensaje de error en
español. La descripción es opcional.

#### Scenario: Alta sin título
- **WHEN** se intenta guardar el formulario de alta sin título
- **THEN** no se crea la incidencia y se muestra un mensaje de error en español

#### Scenario: Alta sin prioridad
- **WHEN** se intenta guardar el formulario de alta sin prioridad
- **THEN** no se crea la incidencia y se muestra un mensaje de error en español

#### Scenario: Alta sin activo
- **WHEN** se intenta guardar el formulario de alta sin activo
- **THEN** no se crea la incidencia y se muestra un mensaje de error en español

### Requirement: Prioridades y estados cerrados
La prioridad de una incidencia MUST ser una de: baja, media, alta o crítica. El estado MUST ser
uno de: abierta, en curso, resuelta o cerrada. Ambos se muestran al usuario con su etiqueta en español.

#### Scenario: Etiquetas en español
- **WHEN** se muestra una incidencia de prioridad crítica en estado abierta
- **THEN** se ven las etiquetas «Crítica» y «Abierta»

### Requirement: Listado de incidencias
El panel SHALL ofrecer un listado de incidencias con título, estado, prioridad, activo, sede,
solicitante, técnico asignado y fecha de alta, ordenado de la más reciente a la más antigua.

#### Scenario: Columnas del listado
- **WHEN** un técnico abre el listado de incidencias
- **THEN** cada fila muestra título, estado, prioridad, activo, sede, solicitante, técnico asignado y fecha de alta

#### Scenario: Incidencias anteriores sin activo
- **WHEN** el listado incluye una incidencia creada antes de existir los activos
- **THEN** la fila muestra «Sin activo» en lugar del activo y la sede

### Requirement: Filtros del listado
El listado SHALL permitir filtrar por estado, por prioridad y por sede. El filtro por sede muestra las
incidencias de los activos de esa sede.

#### Scenario: Filtro por estado
- **WHEN** en un listado con incidencias de distintos estados se filtra por un estado
- **THEN** solo se muestran las incidencias en ese estado

#### Scenario: Filtro por prioridad
- **WHEN** en un listado con incidencias de distintas prioridades se filtra por una prioridad
- **THEN** solo se muestran las incidencias con esa prioridad

#### Scenario: Filtro por sede
- **WHEN** en un listado con incidencias de activos de varias sedes se filtra por una sede
- **THEN** solo se muestran las incidencias de activos de esa sede

### Requirement: Visibilidad por rol
Un solicitante MUST ver solo las incidencias de las que es solicitante, tanto en el listado como
en la ficha. Técnicos y administradores SHALL ver todas las incidencias.

#### Scenario: El solicitante solo ve las suyas
- **WHEN** Sara y Sergio han dado de alta una incidencia cada uno y Sara abre el listado
- **THEN** solo ve la suya

#### Scenario: Acceso denegado a la ficha ajena
- **WHEN** Sara intenta abrir la dirección de la ficha de una incidencia de Sergio
- **THEN** el acceso se deniega

#### Scenario: Técnicos y administradores ven todas
- **WHEN** existen incidencias de varios solicitantes y Tomás Técnico o Ana Admin abren el listado
- **THEN** ven todas las incidencias

### Requirement: Sin edición ni borrado
El título, la descripción y la prioridad de una incidencia MUST NOT poder editarse desde el panel, y
las incidencias MUST NOT poder borrarse. El estado y el técnico asignado solo cambian mediante las
acciones definidas en la capacidad `incident-workflow`.

#### Scenario: No hay acción de borrar
- **WHEN** cualquier usuario consulta el listado o la ficha de una incidencia
- **THEN** no se ofrece ninguna acción para editarla ni borrarla

### Requirement: Activo y sede en la ficha
La ficha de una incidencia SHALL mostrar el activo afectado y la sede a la que pertenece.

#### Scenario: Ficha con activo
- **WHEN** Sara da de alta una incidencia sobre un activo y se abre su ficha
- **THEN** se muestran el activo y la sede a la que pertenece
