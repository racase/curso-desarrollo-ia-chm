# Design

## Context

El modelo `User` autentica todos los roles en el panel, pero todavía no tiene
gestión administrativa ni eliminación lógica. Varias tablas referencian a los
usuarios como solicitante, técnico asignado o autor, por lo que una eliminación
física rompería o impediría conservar la trazabilidad. Véase `proposal.md` para
la motivación y `specs/user-management/spec.md` para el contrato observable.

La implementación debe seguir las convenciones de Laravel 13 y Filament 5 del
proyecto, sin dependencias nuevas y con textos de interfaz en español.

## Goals / Non-Goals

**Goals:**

- Centralizar la autorización de gestión en una policy de Laravel.
- Mantener en una única operación de dominio las reglas que protegen al último
  administrador activo.
- Conservar las relaciones históricas cuando una cuenta se elimina lógicamente.
- Mantener formularios y páginas convencionales de Filament, fáciles de probar.

**Non-Goals:**

- Restaurar cuentas eliminadas desde el panel.
- Añadir roles configurables, invitaciones o recuperación de contraseñas.
- Editar perfiles propios fuera del flujo administrativo.
- Permitir gestionar las incidencias asociadas desde el recurso de usuarios.

## Decisions

### Usar eliminación lógica para las cuentas

Se añadirá `deleted_at` mediante una migración nueva y `SoftDeletes` al modelo
`User`. Las relaciones desde incidencias, cambios de estado y comentarios hacia
usuarios incluirán las cuentas eliminadas para conservar nombres y autorías.

Se descarta eliminar físicamente porque existen claves foráneas restrictivas y
porque perdería trazabilidad. También se descarta anonimizar automáticamente:
no lo exige el alcance y ocultaría información operacional relevante.

### Aplicar autorización con `UserPolicy`

Las capacidades de listar, ver, crear, actualizar y eliminar usuarios exigirán
el rol administrador. Filament utilizará la policy tanto para ocultar la
navegación y las acciones como para denegar rutas directas.

Se descartan comprobaciones dispersas en páginas o componentes porque serían
fáciles de omitir y contravienen la convención del proyecto.

### Centralizar mutaciones y proteger al último administrador

Un servicio de gestión realizará la creación, actualización y eliminación tras
la autorización. Antes de degradar o eliminar un administrador comprobará, en
una transacción, que existe otro administrador activo. Si no existe, lanzará un
error de validación comprensible para la interfaz.

La comprobación se repetirá dentro de la transacción y bloqueará las filas de
administradores consultadas para reducir el riesgo de dos operaciones
concurrentes que eliminen el último acceso. Se descarta limitar esta regla solo
al formulario porque otros puntos de entrada podrían eludirla.

### Usar un recurso Filament convencional sin borrado masivo

El recurso tendrá listado, alta y edición. La tabla mostrará nombre, correo y
rol, y ofrecerá acciones individuales de editar y eliminar. No habrá borrado
masivo para que toda eliminación pase explícitamente por el servicio y pueda
mostrar el error de continuidad administrativa.

El formulario exigirá nombre, correo único, rol y contraseña confirmada en el
alta. En edición la contraseña será opcional y no se enviará al servicio cuando
quede vacía, preservando el hash existente.

## Risks / Trade-offs

- [Dos administradores se degradan o eliminan a la vez] → Ejecutar la cuenta y
  la mutación en una transacción con bloqueo de las filas administrativas.
- [Una relación no incluye usuarios eliminados] → Cubrir cada relación histórica
  con tests de Feature que consulten el registro después del borrado lógico.
- [Una cuenta ya autenticada se elimina] → El borrado lógico impide nuevas
  autenticaciones; las peticiones posteriores deben dejar de resolverla como
  usuario activo mediante el proveedor Eloquent normal.
- [No existe restauración en el panel] → Mantenerla fuera del alcance; los datos
  siguen disponibles para una recuperación administrativa futura.

## Migration Plan

1. Añadir la columna nullable `deleted_at` a `users` mediante una migración nueva.
2. Desplegar el modelo y las relaciones compatibles con cuentas eliminadas.
3. Desplegar policy, servicio y recurso Filament.
4. Si se revierte, retirar primero el código que usa eliminación lógica y luego
   ejecutar el `down` de la migración; no se pierde ninguna fila existente.
