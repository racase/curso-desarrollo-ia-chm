# Prompt para generar el backlog de InfraTicket

Prompt para ejecutar con Codex abierto dentro de `web/`. Genera los issues del backlog
en `docs/issues/`, uno por fichero y con contenido de PRD ligero. Si la carpeta ya
existe, bórrala o renómbrala antes para comparar el resultado.

```text
Vas a preparar el backlog inicial de InfraTicket como ficheros Markdown, uno por
issue. El contenido de cada issue debe ser un PRD ligero que sirva como punto de
partida para debatir y concretar el producto, no una historia de usuario ni una
especificación de implementación ya cerrada. No implementes nada: no toques código,
migraciones ni tests, y no crees la carpeta openspec/.

Antes de escribir, lee AGENTS.md y README.md para conocer el estado real del
proyecto: qué existe ya (login, modelo User con roles, usuarios de prueba) y qué
no existe todavía.

Crea la carpeta docs/issues/ y dentro estos seis ficheros:

1. 01-alta-de-incidencias.md
   Modelo de incidencia con título, descripción, estado, prioridad y solicitante.
   Estados: abierta, en curso, resuelta, cerrada. Prioridades: baja, media, alta,
   crítica. Formulario de alta, listado y filtros por estado y prioridad en el
   panel. Un solicitante solo ve sus incidencias; técnico y administrador ven todas.

2. 02-asignacion-y-seguimiento.md
   Asignar una incidencia a un técnico. Cambiar de estado solo por transiciones
   válidas (no se puede cerrar sin resolver). Historial de cambios de estado
   visible en la ficha. Depende del issue 1.

3. 03-comentarios-en-la-incidencia.md
   Comentarios de los usuarios en la ficha de una incidencia, con autor y fecha.
   Depende del issue 1.

4. 04-gestion-de-usuarios.md
   Gestión de usuarios desde el panel, accesible solo para administradores.
   Listado, alta, edición y eliminación de usuarios, con asignación de los roles
   administrador, técnico o solicitante. No tiene dependencias.

5. 05-sedes-y-activos.md
   Sedes y activos, y vincular cada incidencia a un activo. Depende del issue 1
   y modifica su comportamiento.

6. 06-widget-incidencias-por-estado.md
   Widget en el panel con el número de incidencias abiertas por estado.
   Depende del issue 1.

Cada fichero sigue exactamente esta estructura:

---
title: <título corto en español, sin número>
labels: [feature, web]
depends_on: [<números de los issues de los que depende, o vacío>]
---

## Resumen
Una descripción breve de la capacidad de producto y del valor que aporta.

## Problema y oportunidad
Qué necesidad existe, a quién afecta y por qué merece resolverse.

## Objetivos
Resultados que se quieren conseguir. No los conviertas en tareas técnicas.

## Usuarios afectados
Roles implicados y sus necesidades principales, sin redactar historias de usuario
formales del tipo «Como…, quiero…, para…».

## Alcance
Lista de lo que incluye, en términos de comportamiento observable.

## Fuera de alcance
Lista de lo que no incluye, para evitar que el cambio crezca.

## Requisitos de producto
Lista breve de comportamientos y reglas de negocio necesarios. Mantén el nivel de
detalle propio de un PRD y no escribas casos de prueba ni escenarios
«Dado… cuando… entonces…».

## Criterios de éxito
Señales observables que permitirán valorar si la capacidad resuelve el problema,
sin convertirlas en criterios de aceptación exhaustivos.

## Riesgos y preguntas abiertas
Supuestos, dudas y decisiones que deberán concretarse antes de implementar.

Reglas:
- Escribe en español, con el vocabulario del proyecto.
- Describe el qué, no el cómo: nada de nombres de clases, tablas, columnas ni
  paquetes. Eso se decide después, en la especificación.
- Mantén cada issue conciso y orientado al producto, con estructura de PRD ligero.
  No lo conviertas en una lista de tareas ni intentes cerrar todos los detalles de
  implementación.
- Si una capacidad parece demasiado grande, indícalo en «Riesgos y preguntas
  abiertas» en lugar de dividirla por tu cuenta.
- No inventes funcionalidad que no esté en la lista anterior.

Al terminar, crea docs/issues/README.md con una tabla (número, título,
dependencias) y el orden recomendado, y enséñame un resumen de lo creado.
```
