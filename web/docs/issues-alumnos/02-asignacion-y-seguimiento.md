---
title: Asignación y seguimiento de incidencias
labels: [feature, web]
depends_on: [1]
---

## Contexto

Las incidencias se registran, pero se quedan abiertas para siempre: nadie es responsable
de ellas ni puede hacerlas avanzar, y no queda rastro de lo que ha pasado.

## Historia de usuario

- Como administrador, quiero asignar una incidencia a un técnico, para que tenga un responsable.
- Como técnico, quiero cambiar el estado de mis incidencias, para reflejar cómo va el trabajo.
- Como solicitante, quiero ver el historial de mi incidencia, para saber qué se ha hecho y cuándo.

## Lo que sabemos

- Una incidencia no se puede cerrar si antes no se ha resuelto.
- Cada cambio de estado debe quedar registrado y poder consultarse en la ficha.

## Dudas abiertas

- ¿Cuál es la lista completa de transiciones válidas entre los cuatro estados?
- ¿Se puede reabrir una incidencia resuelta? ¿Y una cerrada?
- ¿Quién puede asignar: solo el administrador, o un técnico puede asignarse una incidencia?
- ¿Quién puede cambiar el estado: solo el técnico asignado, o cualquier técnico?
- ¿Se puede cambiar de técnico una incidencia ya asignada? ¿Queda en el historial?
- ¿Puede pasar a «en curso» una incidencia sin técnico asignado?

## Criterios de aceptación

A redactar por el equipo antes de implementar, como «Dado… cuando… entonces…» y usando
los usuarios de prueba.
