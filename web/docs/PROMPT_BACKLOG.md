# Prompt para generar el backlog de InfraTicket

Prompt para ejecutar con Codex abierto dentro de `web/`. Genera los issues del backlog
en `docs/issues/`, uno por fichero. Si la carpeta ya existe, bórrala o renómbrala antes
para comparar el resultado.

```text
Vas a preparar el backlog inicial de InfraTicket como ficheros Markdown, uno por
issue, para subirlos después a GitHub. No implementes nada: no toques código,
migraciones ni tests, y no crees la carpeta openspec/.

Antes de escribir, lee AGENTS.md y README.md para conocer el estado real del
proyecto: qué existe ya (login, modelo User con roles, usuarios de prueba) y qué
no existe todavía.

Crea la carpeta docs/issues/ y dentro estos cinco ficheros:

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

4. 04-sedes-y-activos.md
   Sedes y activos, y vincular cada incidencia a un activo. Depende del issue 1
   y modifica su comportamiento.

5. 05-widget-incidencias-por-estado.md
   Widget en el panel con el número de incidencias abiertas por estado.
   Depende del issue 1.

Cada fichero sigue exactamente esta estructura:

---
title: <título corto en español, sin número>
labels: [feature, web]
depends_on: [<números de los issues de los que depende, o vacío>]
---

## Contexto
Dos o tres frases: qué problema resuelve y para quién.

## Historia de usuario
Como <rol>, quiero <acción>, para <beneficio>. Una por rol afectado.

## Alcance
Lista de lo que incluye, en términos de comportamiento observable.

## Fuera de alcance
Lista de lo que no incluye, para evitar que el cambio crezca.

## Criterios de aceptación
Lista numerada de comprobaciones verificables, redactadas como
«Dado… cuando… entonces…». Usa los roles y los usuarios de prueba que existen.

## Notas
Dudas abiertas o decisiones que habrá que tomar en la especificación.

Reglas:
- Escribe en español, con el vocabulario del proyecto.
- Describe el qué, no el cómo: nada de nombres de clases, tablas, columnas ni
  paquetes. Eso se decide después, en la especificación.
- Cada issue debe poder hacerse en un solo cambio. Si uno queda demasiado grande,
  dilo en «Notas» en lugar de partirlo.
- En el issue 2, deja «Criterios de aceptación» con un único punto de ejemplo y la
  nota «A completar por el equipo antes de implementar».
- No inventes funcionalidad que no esté en la lista anterior.

Al terminar, crea docs/issues/README.md con una tabla (número, título,
dependencias) y el orden recomendado, y enséñame un resumen de lo creado.
```
