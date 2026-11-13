# Spec Delta

## Purpose

Permite que solicitante y equipo técnico conversen sobre una incidencia mediante comentarios
en su ficha, sin salir de la aplicación.

## ADDED Requirements

### Requirement: Añadir comentarios
Quien puede ver una incidencia (su solicitante, los técnicos y los administradores) SHALL poder
añadir un comentario de texto desde su ficha. El comentario MUST quedar asociado a su autor y a la
fecha y hora en que se guarda.

#### Scenario: Un técnico comenta
- **WHEN** Tomás Técnico abre la ficha de una incidencia de Sara, escribe un comentario y lo guarda
- **THEN** el comentario aparece en la ficha con su nombre y la fecha y hora

#### Scenario: La solicitante responde
- **WHEN** una incidencia de Sara tiene un comentario de Tomás y Sara abre la ficha
- **THEN** ve el comentario y puede añadir otro

### Requirement: Validación del comentario
Un comentario vacío o de más de 2000 caracteres MUST rechazarse con un mensaje de error en español.

#### Scenario: Comentario vacío
- **WHEN** se intenta guardar un comentario vacío
- **THEN** no se crea y se muestra un mensaje de error en español

#### Scenario: Comentario demasiado largo
- **WHEN** se intenta guardar un comentario de más de 2000 caracteres
- **THEN** no se crea y se muestra un mensaje de error en español

### Requirement: Orden de los comentarios
La ficha SHALL mostrar los comentarios del más antiguo al más reciente.

#### Scenario: Varios comentarios
- **WHEN** una incidencia tiene varios comentarios y se abre su ficha
- **THEN** aparecen ordenados del más antiguo al más reciente

### Requirement: Acceso a los comentarios
Quien no puede ver una incidencia MUST NOT poder leer ni añadir comentarios en ella.

#### Scenario: Un solicitante ajeno
- **WHEN** Sergio Solicitante intenta comentar en una incidencia de Sara
- **THEN** el acceso se deniega

### Requirement: Incidencias cerradas
En una incidencia cerrada los comentarios existentes SHALL seguir visibles, pero MUST NOT poder
añadirse comentarios nuevos.

#### Scenario: Incidencia cerrada
- **WHEN** alguien abre la ficha de una incidencia cerrada con comentarios
- **THEN** ve los comentarios y no se le ofrece la acción de comentar
