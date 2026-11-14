# Spec Delta

## MODIFIED Requirements

### Requirement: Datos y valores de una incidencia
El sistema SHALL conservar para cada incidencia un título, una descripción, un
estado, una prioridad, el solicitante que la registró y el activo afectado cuando
se haya identificado.

#### Scenario: Valores de estado admitidos
- **WHEN** una incidencia se almacena o se muestra
- **THEN** su estado es abierta, en curso, resuelta o cerrada

#### Scenario: Valores de prioridad admitidos
- **WHEN** una incidencia se almacena o se muestra
- **THEN** su prioridad es baja, media, alta o crítica

#### Scenario: Activo asociado
- **WHEN** se registra una nueva incidencia
- **THEN** el sistema conserva el activo seleccionado junto con los demás datos del alta

### Requirement: Alta por el solicitante
El sistema SHALL permitir que un usuario con rol solicitante registre una
incidencia propia indicando título, descripción, prioridad y un activo activo.

#### Scenario: Alta correcta
- **WHEN** un solicitante envía un título, una descripción, una prioridad válida y un activo activo
- **THEN** el sistema crea la incidencia con ese usuario como solicitante, el activo seleccionado y estado abierta

#### Scenario: Alta con datos incompletos
- **WHEN** un solicitante omite el título, la descripción, la prioridad o el activo
- **THEN** el sistema rechaza el alta e indica los datos que faltan

#### Scenario: Alta con activo no disponible
- **WHEN** un solicitante intenta registrar una incidencia con un activo inexistente o eliminado
- **THEN** el sistema rechaza el alta

#### Scenario: Alta desde otro rol
- **WHEN** un técnico o administrador intenta acceder al alta de incidencias
- **THEN** el sistema deniega la operación

### Requirement: Incidencias sin edición posterior
El sistema SHALL mantener inmutables el título, la descripción, la prioridad, el
solicitante y el activo después del alta, permitiendo únicamente las acciones
controladas de asignación y transición definidas por la capacidad de seguimiento.

#### Scenario: Usuario intenta editar una incidencia
- **WHEN** cualquier usuario intenta acceder a una acción de edición de una incidencia
- **THEN** el sistema no ofrece la acción y deniega la modificación de los datos del alta

#### Scenario: Usuario autorizado realiza un cambio controlado
- **WHEN** un usuario autorizado asigna un técnico o solicita una transición válida
- **THEN** el sistema modifica únicamente la asignación o el estado correspondiente

## ADDED Requirements

### Requirement: Contexto físico de una incidencia
El sistema SHALL mostrar a los usuarios con acceso a una incidencia el código y
nombre del activo afectado y el nombre de su sede.

#### Scenario: Consulta de una incidencia con activo
- **WHEN** un usuario autorizado abre el listado o detalle de una incidencia con activo
- **THEN** el sistema muestra el código y nombre del activo y el nombre de su sede

#### Scenario: Activo o sede eliminados
- **WHEN** un usuario autorizado consulta una incidencia cuyo activo o sede se eliminó lógicamente
- **THEN** el sistema conserva y muestra el contexto histórico del activo y de la sede

#### Scenario: Incidencia anterior sin activo
- **WHEN** un usuario autorizado consulta una incidencia creada antes de incorporar el catálogo
- **THEN** el sistema muestra que no tiene activo sin impedir la consulta
