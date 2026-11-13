---
title: Sedes y activos vinculados a la incidencia
labels: [feature, web]
depends_on: [1]
---

## Contexto

Una incidencia de mantenimiento siempre afecta a algo concreto que está en un sitio
concreto, y hoy eso solo se puede escribir a mano en la descripción. Este issue añade
las sedes y sus activos y obliga a indicar a qué activo afecta cada incidencia. Cambia
el comportamiento del alta definido en el issue 1.

## Historia de usuario

- Como administrador, quiero mantener la lista de sedes y de activos, para que las
  incidencias se refieran a elementos reales.
- Como solicitante, quiero elegir el activo afectado al dar de alta una incidencia,
  para no tener que describir dónde está.
- Como técnico, quiero filtrar las incidencias por sede, para organizar los desplazamientos.

## Alcance

- Gestión de sedes desde el panel: alta, edición y listado. Solo administradores.
- Gestión de activos desde el panel: alta, edición y listado. Cada activo pertenece a
  una sede. Solo administradores.
- Al dar de alta una incidencia hay que elegir un activo.
- El listado y la ficha de la incidencia muestran el activo y su sede.
- Filtro por sede en el listado de incidencias.
- Datos de ejemplo con varias sedes y activos.

## Fuera de alcance

- Jerarquía de activos (un activo dentro de otro).
- Importación masiva de activos.
- Localización en mapa.
- Historial de mantenimiento por activo.
- Borrar sedes o activos.

## Criterios de aceptación

1. Dado que Ana Admin ha iniciado sesión, cuando da de alta una sede y un activo en esa
   sede, entonces ambos aparecen en sus listados.
2. Dado que Tomás Técnico o Sara Solicitante han iniciado sesión, cuando buscan la gestión
   de sedes y activos en el panel, entonces no tienen acceso a ella.
3. Dado un alta de incidencia sin activo, cuando se intenta guardar, entonces no se crea
   y se muestra un mensaje de error en español.
4. Dado que Sara da de alta una incidencia sobre un activo, cuando se abre su ficha,
   entonces se muestran el activo y la sede a la que pertenece.
5. Dado un listado con incidencias de activos de varias sedes, cuando se filtra por una
   sede, entonces solo se muestran las de activos de esa sede.

## Notas

- ¿Qué pasa con las incidencias creadas antes de este cambio, que no tienen activo?
- ¿Se puede cambiar el activo de una incidencia ya creada?
- Para los datos de ejemplo se pueden usar las mismas sedes que la demo de datos:
  Planta Valencia, Planta Sagunto y Almacén Paterna.
- Este issue modifica una funcionalidad ya entregada. Es un buen caso para ver cómo
  se cambia una especificación existente.
