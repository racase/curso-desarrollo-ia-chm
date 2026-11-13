# Design

## Context

El proyecto dispone de autenticación de Filament y de usuarios con roles
administrador, técnico y solicitante, pero todavía no tiene modelos de negocio ni
recursos del panel. Véanse `proposal.md` para la motivación y
`specs/incident-intake/spec.md` para el comportamiento requerido.

La implementación debe seguir las convenciones de Laravel 13 y Filament 5, usar
enums respaldados por string para valores cerrados, policies para autorización y
pruebas Feature con SQLite en memoria.

## Goals / Non-Goals

**Goals:**

- Introducir un dominio de incidencias pequeño y extensible para los siguientes
  cambios del backlog.
- Centralizar las reglas de autorización y evitar que una consulta del listado
  exponga incidencias ajenas.
- Mantener formularios, tablas y pruebas convencionales de Filament para que el
  proyecto siga siendo fácil de revisar.

**Non-Goals:**

- Diseñar todavía la asignación, las transiciones, el historial, los comentarios,
  los activos o los indicadores.
- Permitir edición o eliminación de incidencias después del alta.
- Añadir paquetes o personalizar la autenticación del panel.

## Decisions

### Modelo de incidencia independiente

Se añadirá un modelo de incidencia con una relación obligatoria al usuario
solicitante y una migración nueva. La factory generará registros válidos y
permitirá que las pruebas expresen estados y prioridades concretos.

Se descarta reutilizar estructuras genéricas o datos JSON porque las relaciones,
los filtros y los siguientes cambios necesitan un dominio explícito y validable.

### Enums para estado y prioridad

El estado y la prioridad se representarán con enums respaldados por string y con
etiquetas en español. Las columnas persistirán sus valores estables en inglés.

Se descartan strings repartidos por formularios y filtros porque duplicarían las
opciones y facilitarían valores incoherentes.

### Recurso de Filament sin edición

El recurso incluirá páginas de listado, creación y detalle. En el alta, el
solicitante se obtendrá del usuario autenticado y el estado será abierta; ambos
datos no serán elegibles desde el formulario. La prioridad sí será seleccionada
por el solicitante.

No se generará una página de edición ni acciones de modificación o eliminación.
Esto mantiene el límite del cambio y reserva las transiciones para el issue #2.

### Autorización centralizada y consulta acotada

Una policy decidirá quién puede listar, crear, consultar, modificar o eliminar.
La misma capa de autorización expondrá el alcance de consulta que el recurso
usará: solicitantes limitados por propiedad y técnicos o administradores sin ese
filtro. El recurso delegará en esa regla y no repetirá condiciones por rol.

Se descarta confiar solo en la autorización del detalle porque el listado podría
revelar registros ajenos antes de abrirlos.

### Pruebas en los límites del producto

Las pruebas Feature cubrirán el modelo y sus enums, el alta de un solicitante,
la denegación para otros roles, la visibilidad del listado y detalle, los filtros
y la ausencia de edición. Se reutilizarán las factories y `RefreshDatabase`.

## Risks / Trade-offs

- [La policy de Laravel no define de forma nativa un alcance de consulta] → usar
  un método explícito y tipado en la policy al que delegue el recurso, manteniendo
  juntas las decisiones de autorización.
- [El estado fijo en abierta limita correcciones administrativas] → mantener el
  alcance inicial y resolver cambios de estado en el issue #2.
- [No permitir edición impide corregir errores tras el alta] → documentar el
  límite y tratar la edición como una decisión de producto posterior.

## Migration Plan

1. Crear la tabla de incidencias mediante una migración nueva y reversible.
2. Desplegar enums, modelo, factory, policy y recurso del panel.
3. Ejecutar la migración y verificar el alta y la consulta con los usuarios de
   prueba.
4. Para revertir, retirar el recurso y revertir la migración, sin modificar la
   tabla de usuarios ni la autenticación existente.
