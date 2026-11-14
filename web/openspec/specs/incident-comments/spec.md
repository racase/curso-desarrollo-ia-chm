# incident-comments Specification

## Purpose

Define una conversación contextual y trazable dentro de cada incidencia,
respetando las mismas reglas de acceso que protegen el caso.

## Requirements

### Requirement: Datos y autoría de un comentario
El sistema SHALL conservar para cada comentario su contenido, la incidencia a
la que pertenece, el usuario autenticado que lo publicó y la fecha de
publicación.

#### Scenario: Autoría automática
- **WHEN** un usuario publica un comentario en una incidencia
- **THEN** el sistema lo vincula a esa incidencia y al usuario autenticado con la fecha actual

### Requirement: Publicación de comentarios
El sistema SHALL permitir publicar comentarios no vacíos desde la ficha de una
incidencia a cualquier usuario que pueda consultarla, con independencia del
estado de la incidencia.

#### Scenario: Usuario autorizado publica un comentario
- **WHEN** un usuario con acceso a una incidencia envía contenido válido
- **THEN** el sistema añade el comentario a la conversación de la incidencia

#### Scenario: Comentario vacío
- **WHEN** un usuario intenta publicar un comentario sin contenido
- **THEN** el sistema rechaza la publicación e indica que el contenido es obligatorio

#### Scenario: Comentario en una incidencia cerrada
- **WHEN** un usuario con acceso publica un comentario válido en una incidencia cerrada
- **THEN** el sistema añade el comentario sin modificar el estado de la incidencia

### Requirement: Acceso a la conversación
El sistema SHALL permitir consultar y publicar comentarios únicamente a los
usuarios que tengan acceso a la incidencia correspondiente.

#### Scenario: Solicitante accede a su conversación
- **WHEN** un solicitante abre una incidencia registrada por ese usuario
- **THEN** el sistema muestra la conversación y permite publicar comentarios

#### Scenario: Solicitante intenta acceder a una conversación ajena
- **WHEN** un solicitante intenta consultar o comentar una incidencia de otro solicitante
- **THEN** el sistema deniega la operación y no revela sus comentarios

#### Scenario: Técnico o administrador accede a la conversación
- **WHEN** un técnico o administrador abre una incidencia
- **THEN** el sistema muestra la conversación y permite publicar comentarios

### Requirement: Conversación cronológica e inmutable
El sistema SHALL mostrar los comentarios del más antiguo al más reciente con su
autor y fecha, sin ofrecer acciones de edición o eliminación.

#### Scenario: Consulta de varios comentarios
- **WHEN** un usuario con acceso abre una incidencia con varios comentarios
- **THEN** el sistema muestra cada contenido, autor y fecha en orden cronológico

#### Scenario: Modificación de un comentario publicado
- **WHEN** cualquier usuario consulta un comentario ya publicado
- **THEN** el sistema no ofrece acciones para editarlo o eliminarlo
