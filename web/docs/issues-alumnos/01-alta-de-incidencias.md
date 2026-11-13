---
title: Alta y consulta de incidencias
labels: [feature, web]
depends_on: []
---

## Contexto

InfraTicket solo tiene hoy el acceso al panel y los usuarios de prueba. Falta lo esencial:
que una persona pueda dar de alta una incidencia de mantenimiento y que el equipo técnico
pueda verla.

## Historia de usuario

- Como solicitante, quiero dar de alta una incidencia y consultar las mías, para saber que mi problema está registrado.
- Como técnico o administrador, quiero ver todas las incidencias y filtrarlas, para saber qué hay pendiente.

## Lo que sabemos

- Una incidencia tiene título, descripción, prioridad (baja, media, alta, crítica) y estado (abierta, en curso, resuelta, cerrada).
- Un solicitante solo debe ver sus propias incidencias.
- En este issue todavía no se asignan ni cambian de estado.

## Dudas abiertas

- ¿Qué campos son obligatorios? ¿Hay una prioridad por defecto?
- ¿Pueden un técnico o un administrador dar de alta una incidencia en nombre de otra persona?
- ¿Se puede editar una incidencia una vez creada? ¿Quién?
- ¿Por qué campos hay que poder filtrar el listado?

## Criterios de aceptación

A redactar por el equipo antes de implementar, como «Dado… cuando… entonces…» y usando
los usuarios de prueba.
