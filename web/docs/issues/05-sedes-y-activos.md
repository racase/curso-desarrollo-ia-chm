---
title: Sedes y activos
labels: [feature, web]
depends_on: [1]
---

## Resumen

Las incidencias de mantenimiento necesitan identificar el activo afectado y
situarlo dentro de las sedes de la organización. Este contexto ayudará a entender
dónde ocurre el problema y sobre qué elemento debe actuarse.

## Problema y oportunidad

Una incidencia descrita únicamente con texto puede no localizar con precisión el
elemento que requiere atención. Disponer de sedes y activos reducirá la ambigüedad
y conectará cada solicitud con su contexto físico.

## Objetivos

- Representar las sedes y los activos relevantes para mantenimiento.
- Vincular cada incidencia con el activo al que se refiere.
- Facilitar la identificación del lugar y elemento afectados.

## Usuarios afectados

- Los solicitantes necesitan indicar el activo afectado al comunicar un problema.
- Técnicos y administradores necesitan reconocer el activo y su contexto al
  consultar una incidencia.

## Alcance

- Gestión de sedes y activos desde el panel.
- Consulta de las sedes y activos disponibles.
- Vinculación de cada incidencia con un activo.
- Visualización del activo relacionado en la información de la incidencia.

## Fuera de alcance

- Inventario de existencias o valoración económica de activos.
- Planes de mantenimiento preventivo.
- Historial propio de reparaciones de un activo.
- Localización geográfica o representación en mapas.

## Requisitos de producto

- El producto debe permitir mantener las sedes y activos necesarios para
  contextualizar incidencias.
- Cada incidencia debe poder identificar el activo afectado.
- Los usuarios con acceso a una incidencia deben poder reconocer el activo
  vinculado y su sede.
- La incorporación de activos debe integrarse con el alta y la consulta definidas
  en el PRD 1.

## Criterios de éxito

- Las incidencias permiten identificar sin ambigüedad el activo afectado.
- Solicitantes, técnicos y administradores pueden reconocer la sede relacionada
  al consultar el caso.
- La información de sedes y activos aporta contexto sin duplicar la descripción
  de la incidencia.

## Riesgos y preguntas abiertas

- Debe decidirse qué roles pueden crear, editar o eliminar sedes y activos.
- Queda por definir si todo activo debe pertenecer obligatoriamente a una sede.
- Debe concretarse si una incidencia puede registrarse cuando el activo todavía
  no está disponible en el sistema.
