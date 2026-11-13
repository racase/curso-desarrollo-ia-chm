# Spec Delta

## Purpose

Ofrece en la página de inicio del panel una visión de conjunto de las incidencias, para conocer la
carga de trabajo de un vistazo.

## ADDED Requirements

### Requirement: Resumen por estado
La página de inicio del panel SHALL mostrar el número de incidencias en cada uno de los cuatro estados
(abierta, en curso, resuelta y cerrada), con su etiqueta en español. Los estados sin incidencias MUST
mostrarse con un cero.

#### Scenario: Recuento para un técnico
- **WHEN** existen tres incidencias abiertas, dos en curso y una resuelta, y Tomás Técnico abre el inicio del panel
- **THEN** ve 3 en «Abierta», 2 en «En curso», 1 en «Resuelta» y 0 en «Cerrada»

#### Scenario: Sin incidencias
- **WHEN** no existe ninguna incidencia y cualquier usuario abre el inicio del panel
- **THEN** ve los cuatro estados con un cero

### Requirement: Visibilidad del resumen
El resumen MUST contar solo las incidencias que el usuario puede ver: técnicos y administradores
cuentan todas; un solicitante, solo aquellas de las que es solicitante.

#### Scenario: Recuento para una solicitante
- **WHEN** de tres abiertas, dos en curso y una resuelta solo una abierta es de Sara, y Sara Solicitante abre el inicio del panel
- **THEN** ve 1 en «Abierta» y 0 en el resto

### Requirement: Acceso al listado filtrado
Cada cifra del resumen SHALL enlazar al listado de incidencias filtrado por su estado.

#### Scenario: Enlace de un estado
- **WHEN** un usuario pulsa la cifra de «En curso»
- **THEN** llega al listado de incidencias filtrado por el estado «En curso»
