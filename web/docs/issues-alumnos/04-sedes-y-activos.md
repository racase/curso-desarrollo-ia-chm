---
title: Sedes y activos vinculados a la incidencia
labels: [feature, web]
depends_on: [1]
---

## Contexto

Una incidencia de mantenimiento siempre afecta a algo concreto que está en un sitio
concreto, y hoy eso solo se puede escribir a mano en la descripción. Este issue cambia
una funcionalidad ya entregada: el alta de incidencias.

## Historia de usuario

- Como administrador, quiero mantener la lista de sedes y de activos, para que las incidencias se refieran a elementos reales.
- Como solicitante, quiero elegir el activo afectado al dar de alta una incidencia, para no tener que describir dónde está.
- Como técnico, quiero filtrar las incidencias por sede, para organizar los desplazamientos.

## Lo que sabemos

- Cada activo pertenece a una sede.
- Toda incidencia nueva debe indicar a qué activo afecta.

## Dudas abiertas

- ¿Quién puede dar de alta y editar sedes y activos?
- ¿Qué pasa con las incidencias creadas antes de este cambio, que no tienen activo?
- ¿Se puede cambiar el activo de una incidencia ya creada?
- ¿Se pueden borrar sedes o activos que ya tienen incidencias?
- ¿Qué datos de ejemplo hacen falta?

## Criterios de aceptación

A redactar por el equipo antes de implementar, como «Dado… cuando… entonces…» y usando
los usuarios de prueba.
