# Backlog de InfraTicket

Issues iniciales de la parte web, uno por fichero. Describen el comportamiento esperado,
no la solución técnica: esa se decide en la especificación de cada cambio.

| N.º | Issue | Depende de |
|---|---|---|
| 1 | [Alta y consulta de incidencias](01-alta-de-incidencias.md) | — |
| 2 | [Asignación y seguimiento de incidencias](02-asignacion-y-seguimiento.md) | 1 |
| 3 | [Comentarios en la incidencia](03-comentarios-en-la-incidencia.md) | 1 |
| 4 | [Sedes y activos vinculados a la incidencia](04-sedes-y-activos.md) | 1 |
| 5 | [Resumen de incidencias por estado en el panel](05-widget-incidencias-por-estado.md) | 1 |

## Orden recomendado

1. Issue 1: es la base de todo lo demás.
2. Issue 2: completa el ciclo de vida de la incidencia.
3. Issues 3 y 5: pequeños e independientes entre sí.
4. Issue 4: el último, porque cambia el alta ya entregada en el issue 1.

## Subirlos a GitHub

La cabecera de cada fichero lleva el título y las etiquetas. Las etiquetas `feature` y
`web` tienen que existir antes en el repositorio.
