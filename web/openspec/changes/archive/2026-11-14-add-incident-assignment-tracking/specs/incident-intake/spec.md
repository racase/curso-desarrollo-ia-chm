# Spec Delta

## MODIFIED Requirements

### Requirement: Incidencias sin edición posterior
El sistema SHALL mantener inmutables el título, la descripción, la prioridad y
el solicitante después del alta, permitiendo únicamente las acciones controladas
de asignación y transición definidas por la capacidad de seguimiento.

#### Scenario: Usuario intenta editar una incidencia
- **WHEN** cualquier usuario intenta acceder a una acción general de edición de una incidencia
- **THEN** el sistema no ofrece la acción y deniega la modificación de los datos del alta

#### Scenario: Usuario autorizado realiza un cambio controlado
- **WHEN** un usuario autorizado asigna un técnico o solicita una transición válida
- **THEN** el sistema modifica únicamente la asignación o el estado correspondiente
