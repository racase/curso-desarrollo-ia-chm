---
title: Alta y consulta de incidencias
labels: [feature, web]
depends_on: []
---

## Resumen

InfraTicket necesita una forma común de registrar y consultar incidencias de
mantenimiento. Esta capacidad será la base del resto del producto y permitirá
que cada rol trabaje con la información que le corresponde.

## Problema y oportunidad

Actualmente el panel no permite comunicar una incidencia ni hacer seguimiento
de las solicitudes existentes. Centralizar la información facilitará que los
solicitantes comuniquen problemas y que técnicos y administradores tengan una
visión compartida del trabajo pendiente.

## Objetivos

- Permitir registrar incidencias con la información necesaria para entenderlas.
- Facilitar la consulta y localización de incidencias por estado y prioridad.
- Respetar la visibilidad que corresponde a cada rol.

## Usuarios afectados

- Los solicitantes, como Sara Solicitante y Sergio Solicitante, necesitan
  registrar incidencias y consultar únicamente las suyas.
- Los técnicos, como Tomás Técnico y Teresa Técnica, necesitan consultar todas
  las incidencias para conocer la demanda de mantenimiento.
- Los administradores, como Ana Admin, necesitan una visión completa de las
  incidencias registradas.

## Alcance

- Registro de una incidencia con título, descripción, estado, prioridad y
  solicitante.
- Estados abierta, en curso, resuelta y cerrada.
- Prioridades baja, media, alta y crítica.
- Formulario de alta y listado de incidencias en el panel.
- Filtros por estado y prioridad.
- Visibilidad limitada a las incidencias propias para solicitantes y completa
  para técnicos y administradores.

## Fuera de alcance

- Asignación a técnicos, transiciones de estado e historial de cambios.
- Comentarios dentro de una incidencia.
- Relación con sedes y activos.
- Indicadores o widgets del panel.

## Requisitos de producto

- Cada incidencia debe conservar su título, descripción, estado, prioridad y
  solicitante.
- Los estados y prioridades disponibles deben limitarse a los valores definidos
  en este PRD.
- El listado debe permitir filtrar por estado y prioridad.
- Un solicitante no debe acceder a incidencias de otros solicitantes.
- Técnicos y administradores deben poder consultar todas las incidencias.

## Criterios de éxito

- Los solicitantes pueden registrar y localizar sus propias incidencias desde el
  panel sin acceder a las de otros usuarios.
- Técnicos y administradores pueden consultar el conjunto completo de
  incidencias y acotarlo mediante los filtros previstos.
- La información registrada permite distinguir con claridad el problema, su
  estado, su prioridad y quién lo ha solicitado.

## Riesgos y preguntas abiertas

- Debe decidirse qué estado y prioridad se proponen al crear una incidencia.
- Debe concretarse si algún rol puede registrar una incidencia en nombre de otra
  persona.
- Queda por definir qué datos pueden modificarse después del alta y por quién.
