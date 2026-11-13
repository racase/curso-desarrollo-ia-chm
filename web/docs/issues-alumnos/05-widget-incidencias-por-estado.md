---
title: Resumen de incidencias por estado en el panel
labels: [feature, web]
depends_on: [1]
---

## Contexto

Al entrar al panel no hay ninguna visión de conjunto: para saber cuánto trabajo hay
pendiente hay que abrir el listado y filtrar estado por estado.

## Historia de usuario

- Como técnico, quiero ver al entrar cuántas incidencias hay en cada estado, para saber la carga de trabajo de un vistazo.
- Como solicitante, quiero ver cuántas de mis incidencias siguen pendientes, para saber cómo van.

## Lo que sabemos

- El resumen va en la página de inicio del panel.

## Dudas abiertas

- ¿Qué incidencias cuenta el resumen para cada rol?
- ¿Se muestran los estados que no tienen ninguna incidencia?
- ¿Debe cada cifra enlazar al listado filtrado por ese estado?
- ¿Se quiere destacar de alguna forma las incidencias de prioridad crítica?

## Criterios de aceptación

A redactar por el equipo antes de implementar, como «Dado… cuando… entonces…» y usando
los usuarios de prueba.
