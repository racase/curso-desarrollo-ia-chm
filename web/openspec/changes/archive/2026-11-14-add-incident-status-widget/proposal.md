# Proposal

## Why

El panel muestra incidencias individuales, pero no permite comprender de un
vistazo cómo se distribuye el trabajo todavía no cerrado. Un resumen por estado
hará visible esa carga sin obligar a abrir primero el listado completo.

## What Changes

- Añadir al dashboard un widget con los recuentos actuales de incidencias en
  estado abierta, en curso y resuelta; las incidencias cerradas quedan fuera del
  resumen de trabajo abierto.
- Mostrar siempre los tres estados relevantes, incluido el valor cero cuando no
  existan incidencias en uno de ellos.
- Hacer el widget visible para administradores, técnicos y solicitantes.
- Calcular para cada usuario únicamente las incidencias que puede consultar según
  las reglas existentes: todas para administradores y técnicos, y solo las
  propias para solicitantes.
- Mantener el widget como resumen informativo, sin enlaces, navegación, filtros
  ni acciones adicionales.

## Capabilities

### New Capabilities

- `incident-status-summary`: resumen del trabajo no cerrado por estado en el
  dashboard, con recuentos ajustados a la visibilidad del usuario.

### Modified Capabilities

Ninguna. Las reglas de visibilidad de `incident-intake` permanecen sin cambios y
se reutilizan para construir el resumen.

## Impact

- Nuevo widget Filament descubierto automáticamente en el panel de InfraTicket.
- Consulta agregada de incidencias reutilizando el alcance de `IncidentPolicy` y
  los valores de `IncidentStatus`.
- Nuevos tests de Feature para presentación, recuentos por estado, ceros,
  exclusión de cerradas y visibilidad por rol.
- Sin cambios de esquema ni dependencias nuevas de Composer o npm.
