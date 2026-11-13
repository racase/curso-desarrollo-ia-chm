# sites-and-assets Specification

## Purpose
Mantiene el catálogo de sedes y de los activos que hay en cada una, para que las incidencias se
refieran a elementos reales.

## Requirements

### Requirement: Gestión de sedes
Un administrador SHALL poder dar de alta, editar y listar sedes. Cada sede MUST tener un nombre
obligatorio y único.

#### Scenario: Alta de una sede
- **WHEN** Ana Admin da de alta la sede «Planta Valencia»
- **THEN** la sede aparece en el listado de sedes

#### Scenario: Nombre de sede repetido
- **WHEN** se intenta dar de alta una sede con el nombre de otra existente
- **THEN** no se crea y se muestra un mensaje de error en español

### Requirement: Gestión de activos
Un administrador SHALL poder dar de alta, editar y listar activos. Cada activo MUST tener un nombre y
pertenecer a una sede. El listado de activos muestra su sede y se puede filtrar por sede.

#### Scenario: Alta de un activo en una sede
- **WHEN** Ana Admin da de alta el activo «Compresor 1» en la sede «Planta Valencia»
- **THEN** el activo aparece en el listado de activos con su sede

#### Scenario: Activo sin sede
- **WHEN** se intenta guardar un activo sin sede
- **THEN** no se crea y se muestra un mensaje de error en español

### Requirement: Acceso solo para administradores
Técnicos y solicitantes MUST NOT tener acceso a la gestión de sedes y activos: ni la ven en la
navegación ni pueden abrir sus páginas.

#### Scenario: Un técnico o una solicitante buscan la gestión
- **WHEN** Tomás Técnico o Sara Solicitante intentan abrir el listado de sedes o de activos
- **THEN** no aparece en su navegación y el acceso se deniega

### Requirement: Sin borrado
Las sedes y los activos MUST NOT poder borrarse desde el panel.

#### Scenario: No hay acción de borrar
- **WHEN** Ana Admin consulta una sede o un activo
- **THEN** no se ofrece ninguna acción para borrarlo
