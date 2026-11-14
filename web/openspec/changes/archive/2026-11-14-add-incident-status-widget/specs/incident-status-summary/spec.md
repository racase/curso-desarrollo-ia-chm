# Spec Delta

## Purpose

Ofrecer en el dashboard una lectura inmediata del trabajo de incidencias que
todavía no está cerrado, respetando la visibilidad de cada usuario.

## ADDED Requirements

### Requirement: Resumen disponible en el dashboard
El sistema SHALL mostrar a todo usuario autenticado del panel un resumen claro
de las incidencias no cerradas agrupadas por estado.

#### Scenario: Usuario abre el dashboard
- **WHEN** un administrador, técnico o solicitante abre el dashboard de InfraTicket
- **THEN** el sistema muestra el widget de incidencias por estado

### Requirement: Recuentos de estados no cerrados
El sistema SHALL mostrar recuentos separados para los estados abierta, en curso
y resuelta, sin incluir las incidencias cerradas en el resumen.

#### Scenario: Incidencias distribuidas por estado
- **WHEN** existen incidencias visibles en los estados abierta, en curso y resuelta
- **THEN** el widget muestra para cada estado el número exacto de incidencias correspondiente

#### Scenario: Estado sin incidencias
- **WHEN** un estado no cerrado no contiene incidencias visibles
- **THEN** el widget mantiene ese estado en el resumen y muestra el valor cero

#### Scenario: Incidencias cerradas
- **WHEN** existen incidencias visibles en estado cerrada
- **THEN** el widget no las suma a ningún recuento ni muestra una categoría de cerradas

### Requirement: Recuentos según la visibilidad del usuario
El sistema SHALL calcular el resumen usando únicamente las incidencias que el
usuario puede consultar con las reglas de visibilidad existentes.

#### Scenario: Solicitante consulta el resumen
- **WHEN** un solicitante abre el dashboard
- **THEN** los recuentos incluyen únicamente las incidencias registradas por ese usuario

#### Scenario: Técnico consulta el resumen
- **WHEN** un técnico abre el dashboard
- **THEN** los recuentos incluyen todas las incidencias registradas

#### Scenario: Administrador consulta el resumen
- **WHEN** un administrador abre el dashboard
- **THEN** los recuentos incluyen todas las incidencias registradas

### Requirement: Recuentos actuales al cargar
El sistema SHALL obtener los recuentos a partir del estado actual de las
incidencias cada vez que se carga el widget.

#### Scenario: Cambian las incidencias antes de recargar
- **WHEN** se crea o cambia de estado una incidencia y el usuario vuelve a cargar el dashboard
- **THEN** el widget refleja la nueva distribución de estados
