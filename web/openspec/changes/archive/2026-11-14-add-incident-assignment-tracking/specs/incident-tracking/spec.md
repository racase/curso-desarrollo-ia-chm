# Spec Delta

## Purpose

Define la asignación responsable de incidencias, su avance por estados válidos y
la trazabilidad visible de todos los cambios de estado.

## ADDED Requirements

### Requirement: Asignación a un técnico
El sistema SHALL permitir que un administrador asigne o reasigne una incidencia
a un usuario con rol técnico.

#### Scenario: Administrador asigna una incidencia
- **WHEN** un administrador selecciona un técnico para una incidencia
- **THEN** el sistema guarda al técnico como responsable y lo muestra en la ficha

#### Scenario: Administrador reasigna una incidencia
- **WHEN** un administrador selecciona otro técnico para una incidencia ya asignada
- **THEN** el sistema sustituye al responsable anterior por el nuevo técnico

#### Scenario: Asignación a un usuario no técnico
- **WHEN** un administrador intenta asignar una incidencia a un usuario que no tiene rol técnico
- **THEN** el sistema rechaza la asignación y conserva al responsable anterior

#### Scenario: Asignación desde otro rol
- **WHEN** un técnico o solicitante intenta asignar una incidencia
- **THEN** el sistema deniega la operación

### Requirement: Transiciones lineales de estado
El sistema SHALL permitir únicamente las transiciones abierta a en curso, en
curso a resuelta y resuelta a cerrada.

#### Scenario: Inicio del trabajo
- **WHEN** un usuario autorizado cambia una incidencia abierta a en curso
- **THEN** el sistema guarda el nuevo estado

#### Scenario: Resolución del trabajo
- **WHEN** un usuario autorizado cambia una incidencia en curso a resuelta
- **THEN** el sistema guarda el nuevo estado

#### Scenario: Cierre de una incidencia resuelta
- **WHEN** un usuario autorizado cambia una incidencia resuelta a cerrada
- **THEN** el sistema guarda el nuevo estado

#### Scenario: Cierre sin resolución
- **WHEN** un usuario intenta cambiar a cerrada una incidencia abierta o en curso
- **THEN** el sistema rechaza el cambio y conserva el estado anterior

#### Scenario: Salto o retroceso de estado
- **WHEN** un usuario intenta una transición distinta del siguiente estado permitido
- **THEN** el sistema rechaza el cambio y conserva el estado anterior

### Requirement: Autorización para cambiar el estado
El sistema SHALL permitir cambiar el estado solo al técnico asignado o a un
administrador.

#### Scenario: Técnico asignado avanza el estado
- **WHEN** el técnico responsable solicita la siguiente transición válida
- **THEN** el sistema permite el cambio

#### Scenario: Técnico no asignado intenta avanzar el estado
- **WHEN** un técnico que no es responsable intenta cambiar el estado
- **THEN** el sistema deniega la operación

#### Scenario: Administrador avanza el estado
- **WHEN** un administrador solicita la siguiente transición válida
- **THEN** el sistema permite el cambio aunque no sea el técnico asignado

#### Scenario: Solicitante intenta avanzar el estado
- **WHEN** un solicitante intenta cambiar el estado de una incidencia
- **THEN** el sistema deniega la operación

### Requirement: Historial de cambios de estado
El sistema SHALL registrar cada transición completada con el estado anterior, el
estado nuevo, el usuario que la realizó y la fecha del cambio.

#### Scenario: Transición completada
- **WHEN** el sistema guarda una transición válida
- **THEN** añade una entrada de historial con los estados, el autor y la fecha

#### Scenario: Transición rechazada
- **WHEN** el sistema rechaza una transición o una operación no autorizada
- **THEN** no añade ninguna entrada al historial

#### Scenario: Consulta del historial
- **WHEN** un usuario con acceso a la incidencia abre su ficha
- **THEN** el sistema muestra el historial de cambios en orden cronológico
