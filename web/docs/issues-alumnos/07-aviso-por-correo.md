---
title: Avisos por correo sobre la incidencia
labels: [feature, web]
depends_on: [2]
---

## Contexto

Hoy nadie se entera de lo que pasa con una incidencia si no entra al panel: el técnico
no sabe que le han asignado una y el solicitante no sabe que la suya está resuelta.

## Historia de usuario

- Como técnico, quiero recibir un correo cuando me asignan una incidencia, para empezar a trabajar sin tener que mirar el panel.
- Como solicitante, quiero recibir un correo cuando mi incidencia se resuelve, para comprobar que el problema ha desaparecido.

## Lo que sabemos

- Los correos van en español y permiten llegar a la ficha de la incidencia.

## Dudas abiertas

- ¿Qué información debe llevar cada correo?
- Si una incidencia se cambia de técnico, ¿a quién se avisa?
- ¿Se avisa también de otros cambios de estado, además de la resolución?
- ¿Se avisa a quien hace él mismo la acción?
- ¿Cómo se comprueba que el correo se envía sin enviarlo de verdad durante el desarrollo?

## Criterios de aceptación

A redactar por el equipo antes de implementar, como «Dado… cuando… entonces…» y usando
los usuarios de prueba.
