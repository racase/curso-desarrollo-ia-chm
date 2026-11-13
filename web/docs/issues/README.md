# Backlog de InfraTicket

Este directorio contiene los PRDs ligeros que definen el backlog inicial de
InfraTicket. Cada documento describe una capacidad de producto y deja las
decisiones de implementación para su especificación posterior.

## Resumen

| Número | Título | Dependencias |
|---:|---|---|
| 1 | [Alta y consulta de incidencias](01-alta-de-incidencias.md) | Ninguna |
| 2 | [Asignación y seguimiento de incidencias](02-asignacion-y-seguimiento.md) | 1 |
| 3 | [Comentarios en la incidencia](03-comentarios-en-la-incidencia.md) | 1 |
| 4 | [Gestión de usuarios](04-gestion-de-usuarios.md) | Ninguna |
| 5 | [Sedes y activos](05-sedes-y-activos.md) | 1 |
| 6 | [Widget de incidencias por estado](06-widget-incidencias-por-estado.md) | 1 |

## Orden recomendado

1. Empezar por el PRD 1, que establece la base de incidencias utilizada por el
   resto de capacidades dependientes.
2. El PRD 4 puede abordarse en paralelo porque parte de los usuarios y roles ya
   existentes y no depende de las incidencias.
3. Continuar con los PRDs 2, 3 y 5 una vez disponible la base del PRD 1. Entre
   ellos no hay dependencias directas.
4. Incorporar el PRD 6 cuando los estados y las reglas de visibilidad del PRD 1
   estén consolidados.
