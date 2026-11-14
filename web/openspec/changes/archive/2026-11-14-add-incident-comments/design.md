# Design

## Context

La ficha de incidencia ya concentra los datos del alta, la asignación, las
transiciones y su historial. La consulta está protegida por `IncidentPolicy` y
el recurso aplica esa misma visibilidad a sus páginas. Véanse `proposal.md` y la
delta `incident-comments` para el comportamiento nuevo.

## Goals / Non-Goals

**Goals:**

- Mantener comentarios, autoría y fechas mediante relaciones Eloquent con
  integridad referencial.
- Reutilizar la autorización de acceso a la incidencia como fuente de verdad
  para consultar y publicar comentarios.
- Integrar publicación y lectura en la ficha existente sin habilitar edición
  posterior.

**Non-Goals:**

- Crear hilos, respuestas, menciones o estados propios de los comentarios.
- Añadir adjuntos, notificaciones, edición o borrado.
- Cambiar la visibilidad de las incidencias o limitar comentarios por estado.

## Decisions

### Comentario como entidad inmutable

Se añadirá `IncidentComment` con contenido, incidencia, autor y timestamps. La
incidencia y el usuario expondrán relaciones; la relación desde la incidencia se
ordenará de más antigua a más reciente. Al eliminar una incidencia se eliminarán
sus comentarios, mientras que el autor quedará protegido para conservar la
trazabilidad hasta que la gestión de usuarios defina otra política compatible.

Se descarta almacenar la conversación como JSON porque perdería integridad,
autoría relacional y facilidad de consulta y prueba.

### Autorización derivada de la incidencia

La policy de incidencia añadirá una capacidad específica para comentar que
exigirá el mismo permiso de consulta. Un servicio recibirá el usuario autenticado
y la incidencia, volverá a autorizar la operación y asignará el autor en el
servidor; el formulario nunca aceptará un identificador de autor.

Se descarta una policy independiente para comentarios porque la operación nace
siempre desde una incidencia y no existe consulta autónoma de comentarios.

### Publicación acotada en la ficha

`ViewIncident` incorporará una acción modal con un campo de texto obligatorio.
La acción delegará la creación en el servicio y estará disponible para todos los
usuarios que puedan consultar el registro, incluso cuando esté cerrado. Los
comentarios se mostrarán en una colección de solo lectura dentro del infolist,
con contenido, autor y fecha.

Se descarta un recurso Filament independiente porque expondría navegación y
operaciones que no forman parte del producto.

### Orden cronológico estable

La relación se ordenará por fecha ascendente y, como desempate, por identificador
ascendente. Así la conversación conserva un orden determinista cuando dos
comentarios comparten timestamp.

Se descarta mostrar primero lo más reciente porque una conversación breve se
comprende mejor en el orden en que ocurrió y coincide con el historial de estado.

## Risks / Trade-offs

- [Un cliente intenta suplantar al autor] → ignorar cualquier autor recibido y
  usar exclusivamente al usuario autenticado en el servicio.
- [Acceso directo a una incidencia ajena] → mantener el filtrado del recurso y
  volver a autorizar en el servicio antes de escribir.
- [La conversación crece y alarga la ficha] → mantener la solución simple para
  este alcance y dejar paginación o carga diferida para una delta futura.
- [Dos comentarios tienen la misma fecha] → ordenar también por identificador.

## Migration Plan

1. Crear la tabla y las relaciones; las incidencias existentes empezarán sin
   comentarios.
2. Desplegar la policy, el servicio y la integración de la ficha.
3. Para revertir, retirar primero la acción y la presentación y después revertir
   la migración, asumiendo la pérdida explícita de los comentarios creados.
