# incident-intake Specification

## Purpose

Define el alta y la consulta segura de incidencias de mantenimiento, incluidos
sus estados, prioridades, filtros y reglas de visibilidad para cada rol.

## Requirements

### Requirement: Datos y valores de una incidencia
El sistema SHALL conservar para cada incidencia un título, una descripción, un
estado, una prioridad y el solicitante que la registró.

#### Scenario: Valores de estado admitidos
- **WHEN** una incidencia se almacena o se muestra
- **THEN** su estado es abierta, en curso, resuelta o cerrada

#### Scenario: Valores de prioridad admitidos
- **WHEN** una incidencia se almacena o se muestra
- **THEN** su prioridad es baja, media, alta o crítica

### Requirement: Alta por el solicitante
El sistema SHALL permitir que un usuario con rol solicitante registre una
incidencia propia indicando título, descripción y prioridad.

#### Scenario: Alta correcta
- **WHEN** un solicitante envía un título, una descripción y una prioridad válida
- **THEN** el sistema crea la incidencia con ese usuario como solicitante y con estado abierta

#### Scenario: Alta con datos incompletos
- **WHEN** un solicitante omite el título, la descripción o la prioridad
- **THEN** el sistema rechaza el alta e indica los datos que faltan

#### Scenario: Alta desde otro rol
- **WHEN** un técnico o administrador intenta acceder al alta de incidencias
- **THEN** el sistema deniega la operación

### Requirement: Consulta según el rol
El sistema SHALL limitar las incidencias consultables según el rol y la propiedad
del registro.

#### Scenario: Solicitante consulta sus incidencias
- **WHEN** un solicitante abre el listado de incidencias
- **THEN** el sistema muestra únicamente las incidencias registradas por ese usuario

#### Scenario: Solicitante intenta consultar una incidencia ajena
- **WHEN** un solicitante accede directamente a una incidencia de otro solicitante
- **THEN** el sistema deniega el acceso

#### Scenario: Técnico consulta incidencias
- **WHEN** un técnico abre el listado o el detalle de incidencias
- **THEN** el sistema permite consultar todas las incidencias

#### Scenario: Administrador consulta incidencias
- **WHEN** un administrador abre el listado o el detalle de incidencias
- **THEN** el sistema permite consultar todas las incidencias

### Requirement: Listado y filtros
El sistema SHALL ofrecer un listado de incidencias con filtros por estado y
prioridad que respeten la visibilidad del usuario.

#### Scenario: Filtrado por estado
- **WHEN** un usuario selecciona un estado en el listado
- **THEN** el sistema muestra las incidencias visibles que tienen ese estado

#### Scenario: Filtrado por prioridad
- **WHEN** un usuario selecciona una prioridad en el listado
- **THEN** el sistema muestra las incidencias visibles que tienen esa prioridad

#### Scenario: Combinación de filtros
- **WHEN** un usuario selecciona a la vez un estado y una prioridad
- **THEN** el sistema muestra las incidencias visibles que cumplen ambos filtros

### Requirement: Incidencias sin edición posterior
El sistema SHALL mantener inmutables el título, la descripción, la prioridad y
el solicitante después del alta, permitiendo únicamente las acciones controladas
de asignación y transición definidas por la capacidad de seguimiento.

#### Scenario: Usuario intenta editar una incidencia
- **WHEN** cualquier usuario intenta acceder a una acción de edición de una incidencia
- **THEN** el sistema no ofrece la acción y deniega la modificación de los datos del alta

#### Scenario: Usuario autorizado realiza un cambio controlado
- **WHEN** un usuario autorizado asigna un técnico o solicita una transición válida
- **THEN** el sistema modifica únicamente la asignación o el estado correspondiente
