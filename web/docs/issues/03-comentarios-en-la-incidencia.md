---
title: Comentarios en la incidencia
labels: [feature, web]
depends_on: [1]
---

## Resumen

Los usuarios necesitan compartir información contextual dentro de la propia
incidencia. Los comentarios reunirán la conversación junto al caso e indicarán
quién realizó cada aportación y cuándo lo hizo.

## Problema y oportunidad

La descripción inicial puede no contener toda la información necesaria para
entender o resolver una incidencia. Si la conversación ocurre fuera de
InfraTicket, se pierde contexto y resulta más difícil reconstruir las decisiones.

## Objetivos

- Centralizar las aportaciones relacionadas con una incidencia.
- Mantener identificado al autor y la fecha de cada comentario.
- Facilitar que los usuarios con acceso al caso consulten su conversación.

## Usuarios afectados

- Solicitantes, técnicos y administradores necesitan aportar y consultar
  información dentro de las incidencias a las que tienen acceso.

## Alcance

- Publicación de comentarios desde la ficha de una incidencia.
- Visualización de los comentarios asociados a la incidencia.
- Identificación del autor y la fecha de cada comentario.

## Fuera de alcance

- Archivos adjuntos en los comentarios.
- Edición o eliminación de comentarios ya publicados.
- Notificaciones sobre nuevos comentarios.
- Conversaciones ajenas a una incidencia.

## Requisitos de producto

- Cada comentario debe quedar vinculado a una incidencia y a su autor.
- Cada comentario debe mostrar la fecha en la que se publicó.
- Solo los usuarios con acceso a la incidencia deben poder consultar su
  conversación o participar en ella.
- Los comentarios deben estar disponibles desde la ficha de la incidencia.

## Criterios de éxito

- Los usuarios pueden completar el contexto de una incidencia sin recurrir a
  canales externos.
- Es posible reconocer con claridad quién realizó cada aportación y cuándo.
- La conversación mantiene las mismas restricciones de acceso que la incidencia.

## Riesgos y preguntas abiertas

- Debe decidirse el orden de presentación de los comentarios.
- Queda por concretar si en el futuro se permitirá editar o retirar comentarios.
- Debe valorarse si todos los roles pueden comentar en cualquier estado de la
  incidencia.
