---
title: Plazo de resolución según la prioridad
labels: [feature, web]
depends_on: [1, 2]
---

## Contexto

Todas las incidencias se tratan igual, sea cual sea su prioridad: nada indica cuál
hay que atender primero ni cuál lleva demasiado tiempo esperando. Mantenimiento necesita
un plazo de resolución que dependa de la prioridad y saber cuándo se incumple.

## Historia de usuario

- Como técnico, quiero ver qué incidencias están vencidas o a punto de vencer, para atenderlas primero.
- Como administrador, quiero saber qué incidencias se resolvieron fuera de plazo, para detectar problemas en el servicio.
- Como solicitante, quiero saber para cuándo debería estar resuelta mi incidencia.

## Lo que sabemos

- El plazo depende de la prioridad: cuanto más alta, menos tiempo.
- Una incidencia está vencida si ha pasado su plazo y sigue sin resolverse.

## Dudas abiertas

- ¿Cuánto tiempo corresponde a cada prioridad?
- ¿El plazo cuenta horas naturales o solo horario laboral? ¿Y los fines de semana?
- ¿Desde cuándo cuenta: desde el alta o desde la asignación?
- ¿Qué pasa con el plazo si una incidencia resuelta se reabre?
- ¿Cómo se ve en el listado una incidencia vencida? ¿Y una a punto de vencer?
- ¿Qué plazo tienen las incidencias que ya existían antes de este cambio?

## Criterios de aceptación

A redactar por el equipo antes de implementar, como «Dado… cuando… entonces…» y usando
los usuarios de prueba.
