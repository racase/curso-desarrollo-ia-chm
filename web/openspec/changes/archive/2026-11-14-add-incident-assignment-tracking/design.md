# Design

## Context

El issue #1 introdujo incidencias de solo lectura con estados tipados, una policy
centralizada y páginas de listado, alta y detalle en Filament. Este cambio debe
añadir operaciones acotadas sin reabrir la edición general ni debilitar la
visibilidad por rol. Véanse `proposal.md` y las deltas de `incident-tracking` e
`incident-intake` para el comportamiento esperado.

## Goals / Non-Goals

**Goals:**

- Mantener las reglas de transición y su trazabilidad en una única capa de
  dominio reutilizable desde la interfaz y las pruebas.
- Conservar la autorización en la policy y hacer que las acciones de Filament
  deleguen en ella.
- Preservar el historial incluso cuando la incidencia cambie de estado varias
  veces.

**Non-Goals:**

- Añadir comentarios, notificaciones o historial de asignaciones.
- Permitir cambios libres sobre los datos originales de la incidencia.
- Introducir saltos, retrocesos o reaperturas en el flujo de estados.

## Decisions

### Técnico asignado en la incidencia

Una migración nueva añadirá a la incidencia una relación opcional con el usuario
técnico responsable. La relación empezará vacía y solo aceptará usuarios con rol
técnico mediante validación de dominio. La eliminación futura de un técnico
podrá dejar la incidencia sin asignación sin eliminarla.

Se descarta una tabla intermedia porque el alcance solo requiere un responsable
actual y no un historial de asignaciones.

### Historial de estados como entidad independiente

Cada transición generará una entrada inmutable vinculada a la incidencia, con
estado anterior, estado nuevo, autor y fecha. La incidencia expondrá la relación
ordenada cronológicamente para mostrarla en la ficha.

Se descarta guardar un array JSON en la incidencia porque dificultaría las
relaciones, la integridad y las pruebas de cada cambio.

### Servicio transaccional para el flujo

Un servicio de dominio ejecutará asignaciones y transiciones. Para cambiar el
estado, validará autorización y siguiente estado, actualizará la incidencia y
creará el historial dentro de una transacción. El enum de estado expondrá cuál es
su siguiente valor permitido.

Se descarta implementar la lógica directamente en acciones de Filament o en
callbacks del modelo para mantener una única entrada comprobable y evitar
historias parciales.

### Permisos específicos en la policy

La policy mantendrá denegada la actualización general y añadirá decisiones
específicas para asignar y cambiar estado. Solo el administrador podrá asignar;
el administrador y el técnico actualmente asignado podrán solicitar una
transición.

Las acciones del panel consultarán esos permisos y el servicio volverá a
autorizarlos antes de modificar datos, de modo que ocultar una acción no sea la
única barrera.

### Acciones acotadas en la ficha

La página de detalle incorporará una acción de asignación con un selector
limitado a técnicos y una acción para avanzar al siguiente estado. No se añadirá
una página de edición. El historial se mostrará como una colección de entradas
de solo lectura dentro de la ficha.

Se descarta mostrar un selector con todos los estados porque permitiría expresar
transiciones inválidas que después habría que rechazar.

## Risks / Trade-offs

- [Dos usuarios intentan avanzar el mismo estado a la vez] → ejecutar la
  transición en una transacción y bloquear la incidencia durante la validación.
- [Se elimina un técnico asignado] → usar una relación anulable que deje la
  incidencia sin responsable y permita una nueva asignación.
- [El autor de un historial se elimina en un cambio posterior] → mantener la
  relación protegida hasta que la gestión de usuarios defina una política de
  conservación compatible.
- [El flujo lineal puede ser insuficiente en el futuro] → concentrar la matriz en
  el enum para ampliarla mediante una delta posterior.

## Migration Plan

1. Añadir la relación opcional de técnico asignado mediante una migración nueva.
2. Crear la tabla de historial de estados y sus relaciones.
3. Desplegar servicio, policy y acciones del panel; las incidencias existentes
   permanecerán abiertas y sin asignar.
4. Para revertir, retirar las acciones y relaciones antes de revertir las dos
   migraciones, sin modificar los datos originales de las incidencias.
