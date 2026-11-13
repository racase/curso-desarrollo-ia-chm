---
title: Avisos por correo sobre la incidencia
labels: [feature, web]
depends_on: [2]
---

## Contexto

Hoy nadie se entera de lo que pasa con una incidencia si no entra al panel: el técnico no
sabe que le han asignado una y el solicitante no sabe que la suya está resuelta.

## Historia de usuario

- Como técnico, quiero recibir un correo cuando me asignan una incidencia, para empezar a
  trabajar sin tener que mirar el panel.
- Como solicitante, quiero recibir un correo cuando mi incidencia se resuelve, para
  comprobar que el problema ha desaparecido.

## Alcance

- Cuando una incidencia se asigna a un técnico, ese técnico recibe un correo con el título,
  la prioridad, el nombre del solicitante y un enlace a la ficha.
- Si la incidencia se cambia de técnico, recibe el correo el técnico nuevo. El anterior no
  recibe nada.
- Cuando una incidencia pasa a «resuelta», su solicitante recibe un correo con el título,
  el nombre de quien la resolvió y un enlace a la ficha.
- Los correos van en español.
- No se avisa a quien hace él mismo la acción.
- Si el envío del correo falla, la asignación o el cambio de estado se mantienen.

## Fuera de alcance

- Avisos de otros cambios de estado y de comentarios nuevos.
- Avisos dentro del panel.
- Preferencias de aviso por usuario y resúmenes periódicos.
- Avisos de vencimiento del plazo (issue 6).

## Criterios de aceptación

1. Dado que Ana Admin asigna una incidencia de Sara a Tomás Técnico, cuando se guarda la
   asignación, entonces Tomás recibe un correo con el título, la prioridad, el nombre de
   Sara y un enlace a la ficha.
2. Dado ese mismo caso, cuando se guarda la asignación, entonces ni Sara, ni Ana, ni
   Teresa Técnica reciben ningún correo.
3. Dada una incidencia asignada a Tomás, cuando Ana la cambia a Teresa, entonces Teresa
   recibe el correo de asignación y Tomás no recibe ninguno.
4. Dada una incidencia de Sara en curso, cuando Tomás la pasa a «resuelta», entonces Sara
   recibe un correo con el título, el nombre de Tomás y un enlace a la ficha.
5. Dada una incidencia en curso, cuando pasa a un estado distinto de «resuelta», entonces
   no se envía ningún correo.
6. Dado un intento de asignación que se rechaza, cuando falla, entonces no se envía
   ningún correo.
7. Dado que Tomás abre el enlace del correo sin haber iniciado sesión, cuando lo abre,
   entonces se le pide iniciar sesión y después llega a la ficha.

## Notas

- En desarrollo los correos no deben enviarse de verdad. Hay que decidir cómo se consultan
  y cómo se comprueban en los tests.
- ¿Debe enviarse el correo en el momento o puede salir unos segundos después?
- ¿Qué pasa si una incidencia se reabre y se vuelve a resolver: se avisa otra vez?
