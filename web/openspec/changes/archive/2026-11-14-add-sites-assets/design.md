# Design

## Context

Las incidencias actuales no tienen referencia a un elemento físico. El alta se
realiza en un recurso Filament exclusivo para solicitantes y el detalle se
comparte entre los tres roles según IncidentPolicy. Véase proposal.md para la
motivación y las especificaciones delta para el comportamiento requerido.

El proyecto usa Laravel 13, Filament 5 y PostgreSQL, pero sus tests se ejecutan
con SQLite en memoria. Los cambios de esquema deben ser migraciones nuevas y los
modelos nuevos requieren factory, seeder cuando aporten datos de ejemplo y tests
de Feature.

## Goals / Non-Goals

**Goals:**

- Modelar sedes y activos con integridad relacional y trazabilidad tras su baja.
- Centralizar las reglas de eliminación y pertenencia a una sede activa.
- Integrar el activo en el alta y consulta sin romper incidencias existentes.
- Reutilizar el patrón de resources, policies y servicios ya empleado en usuarios.

**Non-Goals:**

- Restaurar o eliminar físicamente registros desde el panel.
- Añadir inventario, valoración, mantenimiento preventivo o geolocalización.
- Permitir cambiar el activo de una incidencia después del alta.
- Crear páginas de consulta independientes para técnicos o solicitantes.

## Decisions

### Modelar Site y Asset como entidades con eliminación lógica

La tabla sites tendrá nombre único, dirección nullable, timestamps y deleted_at.
La tabla assets tendrá código único, nombre, site_id, timestamps y deleted_at.
La clave foránea de activo a sede será restrictiva para impedir pérdidas si
alguna operación física se ejecutara fuera del flujo normal.

Ambos modelos usarán SoftDeletes. La relación histórica de activo a sede
incluirá sedes eliminadas. Se descarta almacenar sede y activo como texto en la
incidencia porque duplicaría datos y perdería integridad.

### Mantener nullable la nueva clave de incidencia

Una migración nueva añadirá asset_id nullable a incidents, con clave foránea
restrictiva. La nulabilidad permite migrar las filas existentes sin inventar un
activo. El formulario de nuevas incidencias sí exigirá un activo activo y
validará su existencia en servidor.

La relación de incidencia a activo incluirá activos eliminados y, a través de
ellos, sedes eliminadas. El listado y detalle mostrarán un placeholder para las
incidencias anteriores sin activo. Se descarta rellenar datos históricos
automáticamente porque no existe una fuente fiable para hacerlo.

### Autorizar el catálogo mediante policies

SitePolicy y AssetPolicy permitirán listar, crear, editar y eliminar solo a
administradores. Filament usará viewAny para ocultar la navegación y denegar
rutas directas a técnicos y solicitantes. No se ofrecerán restauración, borrado
forzado ni acciones masivas.

Se descartan comprobaciones dispersas en recursos porque serían fáciles de
eludir y no siguen el patrón de autorización existente.

### Centralizar las mutaciones sensibles del catálogo

Un servicio de catálogo ejecutará las mutaciones que dependen de reglas de
dominio. Validará que todo activo nuevo o editado apunte a una sede activa y
rechazará eliminar una sede mientras conserve activos activos. Las eliminaciones
de sede y activo serán lógicas y pasarán por este servicio.

Los formularios mantendrán además las validaciones de obligatoriedad y unicidad
para ofrecer mensajes inmediatos. La base de datos conservará índices únicos
incluyendo filas eliminadas, por lo que un nombre o código retirado no se podrá
reutilizar accidentalmente.

### Integrar el activo sin ampliar permisos de incidencias

El formulario de incidencia ofrecerá únicamente activos activos, con una
etiqueta que combine código, nombre y sede. Guardará asset_id junto con los datos
ya controlados por el alta. El listado y el infolist cargarán y mostrarán activo
y sede, pero no añadirán acciones de edición.

Las reglas de visibilidad de IncidentPolicy permanecen intactas: reconocer el
contexto físico no concede acceso a incidencias adicionales.

### Añadir datos de ejemplo idempotentes

El seeder principal invocará seeders de sedes y activos con identificadores
estables. Los activos de ejemplo se asociarán por una clave natural de sede para
que migrate:fresh --seed produzca un catálogo útil sin duplicados.

## Risks / Trade-offs

- [Incidencias antiguas sin activo] → Mantener asset_id nullable y mostrar
  Sin activo únicamente para esas filas migradas.
- [Activo o sede eliminados dejan de resolverse] → Usar relaciones
  withTrashed() y cubrir activo, sede y presentación con tests de Feature.
- [Se elimina una sede con activos activos] → Ejecutar el borrado mediante el
  servicio y rechazarlo con un error de validación comprensible.
- [Se intenta seleccionar un id eliminado manipulando el formulario] → Aplicar
  validación de existencia con deleted_at IS NULL, no confiar solo en opciones.
- [Nombres o códigos retirados no pueden reutilizarse] → Mantener unicidad global
  para evitar que un identificador histórico llegue a representar otra entidad.

## Migration Plan

1. Crear las tablas sites y assets con sus índices, claves foráneas y columnas
   de eliminación lógica.
2. Añadir asset_id nullable a incidents para preservar las filas existentes.
3. Desplegar modelos, policies, servicio, resources y la integración de
   incidencias.
4. Poblar sedes y activos de ejemplo de forma idempotente.
5. Para revertir, retirar primero la integración de código, eliminar después la
   clave asset_id y finalmente revertir las tablas de activos y sedes.
