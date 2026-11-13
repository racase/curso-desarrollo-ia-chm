---
title: Comentarios en la incidencia
labels: [feature, web]
depends_on: [1]
---

## Contexto

La descripción inicial de una incidencia casi nunca basta: el técnico necesita preguntar
y el solicitante necesita aclarar. Hoy esa conversación ocurriría fuera de la aplicación
y se perdería. Este issue la lleva a la ficha de la incidencia.

## Historia de usuario

- Como técnico, quiero comentar en una incidencia, para pedir más información al solicitante.
- Como solicitante, quiero responder y leer los comentarios de mi incidencia, para seguir
  la conversación sin salir de la aplicación.

## Alcance

- Añadir un comentario de texto desde la ficha de una incidencia.
- Mostrar los comentarios en la ficha, en orden cronológico, con autor y fecha.
- Puede comentar y leer comentarios quien puede ver la incidencia: su solicitante,
  los técnicos y los administradores.

## Fuera de alcance

- Editar o borrar comentarios.
- Ficheros adjuntos e imágenes.
- Comentarios internos, visibles solo para el equipo técnico.
- Menciones y avisos por correo.

## Criterios de aceptación

1. Dado que Tomás Técnico abre la ficha de una incidencia de Sara, cuando escribe un
   comentario y lo guarda, entonces el comentario aparece en la ficha con su nombre y
   la fecha y hora.
2. Dado que una incidencia de Sara tiene un comentario de Tomás, cuando Sara abre la
   ficha, entonces ve el comentario y puede añadir otro.
3. Dado que una incidencia tiene varios comentarios, cuando se abre la ficha, entonces
   aparecen ordenados del más antiguo al más reciente.
4. Dado un comentario vacío, cuando se intenta guardar, entonces no se crea y se muestra
   un mensaje de error en español.
5. Dado que Sergio Solicitante no puede ver una incidencia de Sara, cuando intenta
   comentar en ella, entonces el acceso se deniega.

## Notas

- ¿Se puede comentar en una incidencia cerrada?
- ¿Hay un límite de longitud para el comentario?
