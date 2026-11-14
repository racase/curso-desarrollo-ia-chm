# asset-management Specification

## Purpose

Define un catálogo seguro de sedes y activos que permita identificar el contexto
físico de las incidencias sin perder su trazabilidad histórica.

## Requirements

### Requirement: Acceso administrativo al catálogo
El sistema SHALL permitir gestionar sedes y activos únicamente a usuarios con
rol administrador.

#### Scenario: Administrador accede al catálogo
- **WHEN** un administrador abre la gestión de sedes o activos
- **THEN** el sistema muestra el listado y las acciones de alta, edición y eliminación

#### Scenario: Técnico intenta gestionar el catálogo
- **WHEN** un técnico intenta abrir el listado o una ruta directa de gestión de sedes o activos
- **THEN** el sistema deniega el acceso

#### Scenario: Solicitante intenta gestionar el catálogo
- **WHEN** un solicitante intenta abrir el listado o una ruta directa de gestión de sedes o activos
- **THEN** el sistema deniega el acceso

### Requirement: Gestión de sedes
El sistema SHALL permitir a un administrador listar, crear y editar sedes
activas con un nombre único obligatorio y una dirección opcional.

#### Scenario: Alta de una sede
- **WHEN** un administrador envía un nombre no utilizado y una dirección opcional
- **THEN** el sistema crea la sede y la muestra en el listado activo

#### Scenario: Alta de sede sin nombre
- **WHEN** un administrador intenta crear una sede sin nombre
- **THEN** el sistema rechaza el alta e indica que el nombre es obligatorio

#### Scenario: Nombre de sede duplicado
- **WHEN** un administrador crea o edita una sede con el nombre de otra sede
- **THEN** el sistema rechaza la operación e indica que el nombre ya está en uso

#### Scenario: Edición de una sede
- **WHEN** un administrador modifica el nombre o la dirección de una sede activa con datos válidos
- **THEN** el sistema guarda los cambios y los muestra en el listado

### Requirement: Gestión de activos
El sistema SHALL permitir a un administrador listar, crear y editar activos
activos con código único, nombre obligatorio y una sede activa obligatoria.

#### Scenario: Alta de un activo
- **WHEN** un administrador envía un código no utilizado, un nombre y una sede activa
- **THEN** el sistema crea el activo y muestra su código, nombre y sede en el listado

#### Scenario: Alta de activo con datos incompletos
- **WHEN** un administrador omite el código, nombre o sede
- **THEN** el sistema rechaza el alta e indica los datos que faltan

#### Scenario: Código de activo duplicado
- **WHEN** un administrador crea o edita un activo con el código de otro activo
- **THEN** el sistema rechaza la operación e indica que el código ya está en uso

#### Scenario: Sede no disponible
- **WHEN** un administrador intenta vincular un activo a una sede inexistente o eliminada
- **THEN** el sistema rechaza la operación

#### Scenario: Edición de un activo
- **WHEN** un administrador modifica el código, nombre o sede de un activo con datos válidos
- **THEN** el sistema guarda los cambios y los muestra en el listado

### Requirement: Eliminación segura del catálogo
El sistema SHALL eliminar sedes y activos de forma lógica, ocultarlos de la
gestión activa y conservar los datos necesarios para el histórico.

#### Scenario: Eliminación de un activo
- **WHEN** un administrador elimina un activo
- **THEN** el sistema lo oculta del catálogo activo y conserva sus datos históricos

#### Scenario: Sede con activos activos
- **WHEN** un administrador intenta eliminar una sede que todavía tiene activos activos
- **THEN** el sistema rechaza la eliminación e indica que antes debe retirar esos activos

#### Scenario: Sede sin activos activos
- **WHEN** un administrador elimina una sede sin activos activos
- **THEN** el sistema la oculta del catálogo activo y conserva sus datos históricos
