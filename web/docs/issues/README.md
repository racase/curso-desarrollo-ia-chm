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
| 6 | [Plazo de resolución según la prioridad](06-plazo-de-resolucion.md) | 1 y 2 |
| 7 | [Avisos por correo sobre la incidencia](07-aviso-por-correo.md) | 2 |

## Orden recomendado

1. Issue 1: es la base de todo lo demás.
2. Issue 2: completa el ciclo de vida de la incidencia.
3. Issues 3 y 5: pequeños e independientes entre sí.
4. Issue 4: cambia el alta ya entregada en el issue 1.
5. Issues 6 y 7: los de más reglas de negocio; necesitan el issue 2 terminado.

## Subirlos a GitHub

La cabecera de cada fichero lleva el título y las etiquetas. Las etiquetas `feature` y
`web` tienen que existir antes en el repositorio.
