# Design

## Context

El panel ya usa el dashboard estándar de Filament y descubre automáticamente los
widgets en `app/Filament/Widgets`. No existe todavía ningún widget propio. Las
incidencias almacenan su estado mediante `IncidentStatus` y `IncidentPolicy`
centraliza el alcance visible: los solicitantes ven las suyas y los demás roles
ven todas. Véanse `proposal.md` y la spec delta para el comportamiento esperado.

El proyecto usa Laravel 13, Filament 5 y PostgreSQL; los tests se ejecutan con
SQLite en memoria. No se necesitan cambios de esquema ni dependencias nuevas.

## Goals / Non-Goals

**Goals:**

- Reutilizar exactamente las reglas de visibilidad del listado de incidencias.
- Obtener los tres recuentos con una consulta agregada portable entre PostgreSQL
  y SQLite.
- Mantener visibles los estados sin filas mediante valores cero explícitos.
- Integrarse en el dashboard con el patrón nativo y auto descubierto de Filament.

**Non-Goals:**

- Crear una página de dashboard personalizada.
- Añadir polling, gráficos, tendencias, enlaces o acciones desde las métricas.
- Introducir caché o persistir agregados derivados.
- Cambiar los estados o las reglas de autorización de las incidencias.

## Decisions

### Usar un StatsOverviewWidget descubierto por el panel

Se generará un widget de estadísticas en `app/Filament/Widgets` mediante Artisan.
Su método de estadísticas devolverá siempre tres elementos, en el orden abierta,
en curso y resuelta, usando las etiquetas españolas de `IncidentStatus`.
Filament ya descubre ese directorio, por lo que no se registrará manualmente en
el provider. Se descarta una vista Blade propia porque el componente nativo
expresa el resumen sin añadir presentación ni mantenimiento innecesarios.

El widget no configurará URL ni acciones en sus estadísticas y desactivará el
polling automático. Cada nueva carga del dashboard reconstruirá los valores con
los datos actuales, que es el nivel de actualización requerido.

### Reutilizar IncidentPolicy para construir la consulta visible

El widget obtendrá el usuario autenticado y pasará `Incident::query()` por
`IncidentPolicy::scopeQuery`. Así el resumen y el listado comparten una única
definición de visibilidad, evitando duplicar condiciones por rol. Si no existe un
usuario autenticado, el widget no será visible ni ejecutará una consulta.

Se descarta repetir la condición `requester_id` en el widget porque podría
divergir de futuros cambios de autorización.

### Resolver todos los recuentos con una agregación y ceros por defecto

La consulta filtrará explícitamente `open`, `in_progress` y `resolved`, agrupará
por `status` y contará las filas. El resultado se combinará con un mapa inicial
que contiene cero para los tres estados. Las incidencias `closed` no participarán
en la consulta ni aparecerán como una cuarta estadística.

Se descartan tres consultas independientes porque aumentan accesos a base de
datos y pueden observar instantes ligeramente diferentes. También se descarta
contar todos los estados y ocultar cerradas en presentación, ya que mezclaría
datos fuera del alcance del widget.

### Verificar el widget como componente y desde el dashboard

Los tests de Feature crearán incidencias en los cuatro estados y probarán el
widget con cada rol. Comprobarán etiquetas, valores, ceros, exclusión de cerradas
y aislamiento entre solicitantes. Un test HTTP del dashboard confirmará que el
widget auto descubierto aparece para usuarios autenticados.

## Risks / Trade-offs

- [Las reglas del widget divergen del listado] → Aplicar siempre
  `IncidentPolicy::scopeQuery` y cubrir cada rol con tests.
- [Una agrupación omite estados sin filas] → Combinar el resultado con ceros
  iniciales antes de crear las estadísticas.
- [Una incidencia cerrada altera el resumen] → Filtrar la consulta por los tres
  estados incluidos y probar explícitamente la exclusión.
- [Se añade en el futuro otro estado no cerrado] → Mantener la lista explícita y
  actualizar conjuntamente spec, widget y tests cuando cambie el producto.
- [La consulta agregada difiere entre motores] → Usar únicamente `whereIn`,
  `groupBy` y `count`, compatibles con PostgreSQL y SQLite.

## Migration Plan

1. Generar y configurar el widget de estadísticas.
2. Añadir tests de recuentos y visibilidad por rol.
3. Desplegar sin migraciones; el descubrimiento del panel lo hará visible.
4. Para revertir, retirar el widget y sus tests, sin cambios de datos.
