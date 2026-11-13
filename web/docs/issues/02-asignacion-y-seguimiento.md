---
title: Asignación y seguimiento de incidencias
labels: [feature, web]
depends_on: [1]
---

## Contexto

Con el issue 1 las incidencias se registran, pero se quedan abiertas para siempre: nadie
es responsable de ellas ni puede hacerlas avanzar. Este issue añade el trabajo del equipo
técnico sobre la incidencia y deja rastro de lo que ha pasado.

## Historia de usuario

- Como administrador, quiero asignar una incidencia a un técnico, para que tenga un responsable.
- Como técnico, quiero cambiar el estado de mis incidencias, para reflejar cómo va el trabajo.
- Como solicitante, quiero ver el historial de mi incidencia, para saber qué se ha hecho y cuándo.

## Alcance

- Asignar una incidencia a un usuario con rol de técnico.
- Cambiar el estado de una incidencia, solo por transiciones válidas. Como mínimo, una
  incidencia no se puede cerrar si antes no se ha resuelto.
- Registrar cada cambio de estado: estado anterior, estado nuevo, quién lo hizo y cuándo.
- Mostrar ese historial en la ficha de la incidencia, del más reciente al más antiguo.
- El listado muestra el técnico asignado.

## Fuera de alcance

- Avisos por correo al asignar o al cambiar de estado.
- Plazos de resolución según la prioridad.
- Reparto automático de incidencias entre técnicos.
- Registrar en el historial cambios que no sean de estado.

## Criterios de aceptación

A completar por el equipo antes de implementar. Un ejemplo del nivel de detalle esperado:

1. Dado que una incidencia está en estado «en curso», cuando un técnico intenta pasarla
   a «cerrada», entonces el cambio se rechaza con un mensaje en español y la incidencia
   sigue «en curso».

## Notas

- Hay que definir la lista completa de transiciones válidas entre los cuatro estados.
  ¿Se puede reabrir una incidencia resuelta o cerrada?
- ¿Quién puede asignar: solo el administrador, o un técnico puede asignarse una incidencia?
- ¿Quién puede cambiar el estado: solo el técnico asignado, o cualquier técnico?
- ¿Se puede cambiar de técnico una incidencia ya asignada? ¿Queda en el historial?
- ¿Puede pasar a «en curso» una incidencia sin técnico asignado?
