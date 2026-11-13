---
title: Asignación y seguimiento de incidencias
labels: [feature, web]
depends_on: [1]
---

## Resumen

Las incidencias necesitan avanzar desde su registro hasta su cierre con una
persona técnica responsable y un recorrido de estados comprensible. El historial
permitirá conocer la evolución de cada caso desde su propia ficha.

## Problema y oportunidad

Registrar incidencias no basta para coordinar el trabajo de mantenimiento. Sin
asignación, reglas de avance e historial, resulta difícil saber quién se ocupa de
cada caso, cuál es su situación real y cómo ha evolucionado.

## Objetivos

- Hacer visible qué técnico tiene asignada cada incidencia.
- Evitar cambios de estado incoherentes durante el seguimiento.
- Conservar una trazabilidad comprensible de los cambios de estado.

## Usuarios afectados

- Técnicos y administradores necesitan identificar la responsabilidad y el
  progreso de cada incidencia.
- Los solicitantes necesitan consultar la evolución de las incidencias a las que
  tienen acceso.

## Alcance

- Asignación de una incidencia a un técnico.
- Cambio de estado mediante transiciones válidas.
- Prohibición de cerrar una incidencia que no esté resuelta.
- Historial de cambios de estado visible en la ficha de la incidencia.

## Fuera de alcance

- Alta, listado y filtros básicos de incidencias, cubiertos por el PRD 1.
- Comentarios de usuarios.
- Gestión de usuarios y roles.
- Notificaciones o avisos sobre asignaciones y cambios.

## Requisitos de producto

- Una incidencia debe poder asociarse a un usuario con rol técnico.
- Los cambios de estado deben respetar el recorrido permitido.
- El paso a cerrada solo debe ser posible desde el estado resuelta.
- La ficha debe mostrar el historial de cambios de estado de la incidencia.
- El seguimiento debe respetar las reglas de visibilidad definidas en el PRD 1.

## Criterios de éxito

- El equipo puede identificar al técnico responsable de una incidencia.
- No se producen cierres de incidencias que no hayan sido resueltas previamente.
- Los usuarios con acceso a una incidencia pueden comprender su evolución desde
  la ficha.

## Riesgos y preguntas abiertas

- Debe definirse la matriz completa de transiciones válidas entre estados.
- Debe decidirse qué roles pueden asignar técnicos y cambiar estados.
- Queda por concretar qué información acompaña a cada entrada del historial.
