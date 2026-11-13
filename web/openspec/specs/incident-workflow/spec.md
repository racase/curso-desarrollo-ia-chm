# incident-workflow Specification

## Purpose
Define quién se responsabiliza de cada incidencia, cómo avanza por sus estados y el rastro que
queda de cada cambio de estado.

## Requirements

### Requirement: Asignación a un técnico
Un administrador SHALL poder asignar una incidencia que no esté cerrada a un usuario con rol de
técnico, y cambiar el técnico asignado. Solo se MUST poder elegir usuarios con rol de técnico.
Técnicos y solicitantes MUST NOT poder asignar.

#### Scenario: El administrador asigna una incidencia
- **WHEN** Ana Admin asigna una incidencia abierta a Tomás Técnico
- **THEN** la incidencia queda con Tomás como técnico asignado

#### Scenario: El administrador reasigna una incidencia
- **WHEN** Ana Admin cambia el técnico de una incidencia asignada a Tomás por Teresa Técnica
- **THEN** la incidencia queda asignada a Teresa

#### Scenario: Solo técnicos son asignables
- **WHEN** se intenta asignar una incidencia a un usuario que no es técnico
- **THEN** la asignación se rechaza y la incidencia no cambia

#### Scenario: Un técnico no puede asignar
- **WHEN** Tomás Técnico consulta la ficha de una incidencia
- **THEN** no se le ofrece la acción de asignar

### Requirement: Transiciones de estado válidas
El estado de una incidencia MUST cambiar solo por estas transiciones: abierta → en curso;
en curso → abierta o resuelta; resuelta → en curso o cerrada. Cerrada es un estado final.
Cualquier otro cambio MUST rechazarse con un mensaje en español sin modificar la incidencia.

#### Scenario: No se cierra una incidencia en curso
- **WHEN** un técnico intenta pasar a «cerrada» una incidencia «en curso»
- **THEN** el cambio se rechaza con un mensaje en español y la incidencia sigue «en curso»

#### Scenario: Ciclo completo
- **WHEN** el técnico asignado pasa una incidencia de «abierta» a «en curso», luego a «resuelta» y después a «cerrada»
- **THEN** cada cambio se acepta y la incidencia termina «cerrada»

#### Scenario: Reabrir una incidencia resuelta
- **WHEN** el técnico asignado pasa una incidencia «resuelta» a «en curso»
- **THEN** el cambio se acepta

#### Scenario: Una incidencia cerrada no cambia
- **WHEN** alguien intenta cambiar el estado de una incidencia «cerrada»
- **THEN** el cambio se rechaza y no se ofrece la acción de cambiar estado

### Requirement: En curso requiere técnico
Una incidencia MUST NOT pasar a «en curso» si no tiene un técnico asignado.

#### Scenario: Incidencia sin técnico
- **WHEN** un administrador intenta pasar a «en curso» una incidencia sin técnico asignado
- **THEN** el cambio se rechaza con un mensaje en español y la incidencia sigue «abierta»

### Requirement: Quién cambia el estado
Solo el técnico asignado a la incidencia o un administrador SHALL poder cambiar su estado.
Otros técnicos y los solicitantes MUST NOT poder hacerlo.

#### Scenario: El técnico asignado cambia el estado
- **WHEN** Tomás Técnico, asignado a la incidencia, la pasa a «en curso»
- **THEN** el cambio se acepta

#### Scenario: Otro técnico no puede cambiar el estado
- **WHEN** Teresa Técnica consulta una incidencia asignada a Tomás
- **THEN** no se le ofrece la acción de cambiar estado

#### Scenario: El solicitante no puede cambiar el estado
- **WHEN** Sara Solicitante consulta la ficha de su incidencia
- **THEN** no se le ofrece la acción de cambiar estado

### Requirement: Historial de cambios de estado
Cada cambio de estado aceptado MUST registrar el estado anterior, el estado nuevo, quién lo hizo y
cuándo. La ficha de la incidencia SHALL mostrar ese historial del más reciente al más antiguo a
cualquiera que pueda ver la incidencia. Los cambios rechazados y las asignaciones no se registran.

#### Scenario: Registro de un cambio
- **WHEN** Tomás pasa una incidencia de «abierta» a «en curso»
- **THEN** el historial muestra «Abierta» → «En curso», a Tomás y la fecha y hora del cambio

#### Scenario: Orden del historial
- **WHEN** una incidencia ha pasado por varios estados y su solicitante abre la ficha
- **THEN** ve todos los cambios, del más reciente al más antiguo
