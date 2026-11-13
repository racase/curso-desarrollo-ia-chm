---
title: Plazo de resolución según la prioridad
labels: [feature, web]
depends_on: [1, 2]
---

## Contexto

Todas las incidencias se tratan igual, sea cual sea su prioridad: nada indica cuál hay
que atender primero ni cuál lleva demasiado tiempo esperando. Mantenimiento necesita un
plazo de resolución que dependa de la prioridad y saber cuándo se incumple.

## Historia de usuario

- Como técnico, quiero ver qué incidencias están vencidas o a punto de vencer, para
  atenderlas primero.
- Como administrador, quiero saber qué incidencias se resolvieron fuera de plazo, para
  detectar problemas en el servicio.
- Como solicitante, quiero saber para cuándo debería estar resuelta mi incidencia.

## Alcance

- Cada incidencia tiene una fecha límite de resolución, calculada al darla de alta a
  partir de su prioridad:

  | Prioridad | Plazo |
  |---|---|
  | Crítica | 4 horas |
  | Alta | 24 horas |
  | Media | 72 horas |
  | Baja | 7 días |

- El plazo cuenta horas naturales desde el momento del alta.
- Una incidencia está **vencida** si ha pasado su fecha límite y no está resuelta ni cerrada.
- Una incidencia está **a punto de vencer** si le queda menos de una cuarta parte de su
  plazo y no está resuelta ni cerrada.
- El listado y la ficha muestran la fecha límite, y distinguen a simple vista las
  incidencias vencidas y las que están a punto de vencer.
- Filtro en el listado para ver solo las vencidas.
- Al resolver una incidencia queda registrado si se resolvió dentro o fuera de plazo,
  y la ficha lo muestra.
- Si una incidencia resuelta se reabre, conserva su fecha límite original.
- Las incidencias que ya existían reciben su fecha límite calculada desde su fecha de alta.

## Fuera de alcance

- Horario laboral, fines de semana y festivos.
- Pausar el plazo mientras se espera respuesta del solicitante.
- Plazos configurables desde el panel.
- Avisos por correo cuando una incidencia vence o está a punto de vencer.
- Informes de cumplimiento.

## Criterios de aceptación

1. Dado que Sara da de alta una incidencia crítica a las 10:00, cuando se abre su ficha,
   entonces la fecha límite es ese mismo día a las 14:00.
2. Dado que se dan de alta a la vez una incidencia alta, una media y una baja, cuando se
   consultan, entonces sus fechas límite son 24 horas, 72 horas y 7 días después del alta.
3. Dada una incidencia crítica abierta dada de alta hace 5 horas, cuando Tomás abre el
   listado, entonces aparece marcada como vencida.
4. Dada una incidencia crítica abierta dada de alta hace 3 horas y media, cuando Tomás abre
   el listado, entonces aparece marcada como a punto de vencer y no como vencida.
5. Dada una incidencia crítica dada de alta hace 5 horas y ya resuelta, cuando se abre el
   listado, entonces no aparece marcada como vencida.
6. Dado un listado con incidencias vencidas y no vencidas, cuando se aplica el filtro de
   vencidas, entonces solo se muestran las vencidas.
7. Dada una incidencia crítica dada de alta hace 2 horas, cuando Tomás la resuelve, entonces
   la ficha indica que se resolvió dentro de plazo.
8. Dada una incidencia crítica dada de alta hace 6 horas, cuando Tomás la resuelve, entonces
   la ficha indica que se resolvió fuera de plazo.
9. Dada una incidencia resuelta fuera de plazo que se reabre, cuando se abre el listado,
   entonces vuelve a aparecer como vencida y su fecha límite no ha cambiado.
10. Dado que Sara abre la ficha de una incidencia suya, cuando la consulta, entonces ve la
    fecha límite.

## Notas

- Las pruebas dependen del paso del tiempo: hay que poder fijar la hora en los tests.
- Si una incidencia se resuelve, se reabre y se vuelve a resolver, ¿qué resolución cuenta
  para decidir si fue dentro o fuera de plazo?
- Hoy la prioridad no se puede editar. Si en el futuro se puede, habrá que decidir si la
  fecha límite se recalcula.
- El resumen por estado del issue 5 no cambia en este issue.
