# user-management Specification

## Purpose

Define la administración segura de cuentas y roles del panel, preservando el
acceso administrativo y la trazabilidad histórica de las incidencias.

## Requirements

### Requirement: Acceso exclusivo para administradores
El sistema SHALL permitir gestionar usuarios únicamente a los usuarios con rol
administrador.

#### Scenario: Administrador accede a la gestión
- **WHEN** un administrador abre la gestión de usuarios
- **THEN** el sistema muestra el listado y las acciones de alta, edición y eliminación

#### Scenario: Técnico intenta acceder a la gestión
- **WHEN** un técnico intenta abrir el listado o una ruta directa de gestión de usuarios
- **THEN** el sistema deniega el acceso

#### Scenario: Solicitante intenta acceder a la gestión
- **WHEN** un solicitante intenta abrir el listado o una ruta directa de gestión de usuarios
- **THEN** el sistema deniega el acceso

### Requirement: Listado de usuarios activos
El sistema SHALL mostrar a los administradores los usuarios activos con su
nombre, correo electrónico y rol.

#### Scenario: Consulta del listado
- **WHEN** un administrador abre el listado de usuarios
- **THEN** el sistema muestra el nombre, correo y rol de cada usuario activo

#### Scenario: Usuario eliminado lógicamente
- **WHEN** un administrador abre el listado después de eliminar un usuario
- **THEN** el usuario eliminado no aparece entre las cuentas activas

### Requirement: Alta de usuarios
El sistema SHALL permitir a un administrador crear un usuario indicando nombre,
correo único, contraseña y uno de los roles administrador, técnico o solicitante.

#### Scenario: Alta correcta
- **WHEN** un administrador envía todos los datos requeridos con un correo no utilizado y un rol válido
- **THEN** el sistema crea la cuenta y la muestra en el listado

#### Scenario: Alta con datos incompletos
- **WHEN** un administrador omite el nombre, correo, contraseña o rol
- **THEN** el sistema rechaza el alta e indica los datos que faltan

#### Scenario: Alta con correo duplicado
- **WHEN** un administrador intenta crear una cuenta con el correo de otro usuario
- **THEN** el sistema rechaza el alta e indica que el correo ya está en uso

#### Scenario: Alta con rol no admitido
- **WHEN** un administrador intenta asignar un rol distinto de administrador, técnico o solicitante
- **THEN** el sistema rechaza el alta

### Requirement: Edición de usuarios
El sistema SHALL permitir a un administrador modificar el nombre, correo y rol
de un usuario, y cambiar su contraseña solo cuando indique una nueva.

#### Scenario: Edición sin nueva contraseña
- **WHEN** un administrador guarda cambios dejando vacía la nueva contraseña
- **THEN** el sistema actualiza los demás datos y conserva la contraseña actual

#### Scenario: Edición con nueva contraseña
- **WHEN** un administrador guarda una nueva contraseña válida
- **THEN** el sistema actualiza la contraseña de la cuenta

#### Scenario: Edición con correo duplicado
- **WHEN** un administrador asigna a un usuario el correo de otra cuenta
- **THEN** el sistema rechaza el cambio e indica que el correo ya está en uso

### Requirement: Continuidad de la administración
El sistema MUST impedir que una eliminación o un cambio de rol deje la
aplicación sin ningún administrador activo.

#### Scenario: Degradación del último administrador
- **WHEN** un administrador intenta cambiar el rol del único administrador activo
- **THEN** el sistema rechaza el cambio y conserva su rol de administrador

#### Scenario: Eliminación del último administrador
- **WHEN** un administrador intenta eliminar al único administrador activo
- **THEN** el sistema rechaza la eliminación y mantiene activa la cuenta

#### Scenario: Cambio con otro administrador activo
- **WHEN** existen al menos dos administradores activos y uno de ellos se degrada o elimina
- **THEN** el sistema permite la operación y mantiene al menos un administrador activo

### Requirement: Eliminación lógica y trazabilidad
El sistema SHALL eliminar usuarios de forma lógica para conservar sus relaciones
y su autoría en el historial de incidencias.

#### Scenario: Eliminación de una cuenta
- **WHEN** un administrador elimina un usuario que no es el último administrador activo
- **THEN** el sistema desactiva la cuenta, impide su acceso y la oculta de la gestión activa

#### Scenario: Consulta de una incidencia relacionada
- **WHEN** un usuario autorizado consulta una incidencia, comentario o cambio de estado relacionado con una cuenta eliminada
- **THEN** el sistema conserva y muestra la identidad de esa cuenta en el registro histórico
