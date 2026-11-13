---
title: Alta y consulta de incidencias
labels: [feature, web]
depends_on: []
---

## Contexto

InfraTicket solo tiene hoy el acceso al panel y los usuarios de prueba. Falta lo esencial:
que una persona pueda dar de alta una incidencia de mantenimiento y que el equipo técnico
pueda verla. Es la base sobre la que se apoyan el resto de issues.

## Historia de usuario

- Como solicitante, quiero dar de alta una incidencia y consultar las mías, para saber que
  mi problema está registrado.
- Como técnico, quiero ver todas las incidencias y filtrarlas, para saber qué hay pendiente.
- Como administrador, quiero ver todas las incidencias, para tener una visión global.

## Alcance

- Dar de alta una incidencia con título, descripción y prioridad.
- Prioridades posibles: baja, media, alta y crítica.
- Estados posibles: abierta, en curso, resuelta y cerrada. Toda incidencia nueva nace abierta.
- La incidencia queda asociada a la persona que la da de alta, como solicitante.
- Listado de incidencias en el panel, con título, estado, prioridad, solicitante y fecha de alta.
- Filtros por estado y por prioridad en el listado.
- Un solicitante solo ve sus propias incidencias. Técnicos y administradores ven todas.

## Fuera de alcance

- Asignar la incidencia a un técnico y cambiarla de estado (issue 2).
- Comentarios (issue 3).
- Sedes y activos (issue 4).
- Avisos por correo.
- Borrar incidencias.

## Criterios de aceptación

1. Dado que Sara Solicitante ha iniciado sesión, cuando da de alta una incidencia con título,
   descripción y prioridad, entonces la incidencia aparece en su listado en estado «abierta»
   y con ella como solicitante.
2. Dado un formulario de alta sin título o sin prioridad, cuando se intenta guardar, entonces
   no se crea la incidencia y se muestra un mensaje de error en español.
3. Dado que Sara y Sergio han dado de alta una incidencia cada uno, cuando Sara abre el
   listado, entonces solo ve la suya.
4. Dado que Sara conoce la dirección de la ficha de una incidencia de Sergio, cuando intenta
   abrirla, entonces el acceso se deniega.
5. Dado que existen incidencias de varios solicitantes, cuando Tomás Técnico o Ana Admin
   abren el listado, entonces ven todas.
6. Dado un listado con incidencias de distintos estados y prioridades, cuando se filtra por
   un estado o por una prioridad, entonces solo se muestran las que coinciden.

## Notas

- ¿Pueden un técnico o un administrador dar de alta una incidencia en nombre de otra persona?
- ¿Quién puede editar el título, la descripción o la prioridad una vez creada?
- ¿Hay una prioridad por defecto en el formulario?
