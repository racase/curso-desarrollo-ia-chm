# Design

## Context

`IncidentResource` tiene formulario (título, prioridad, descripción), tabla con filtros por estado y
prioridad e infolist. Todas las políticas de autorización viven en `app/Policies`. Ver `proposal.md`.

## Goals / Non-Goals

**Goals:**
- Catálogo mínimo de sedes y activos, con autorización por policy y recursos generados con Artisan.
- Cambiar el alta sin romper las incidencias ya existentes.

**Non-Goals:**
- Jerarquía de activos, importación masiva, mapas, historial por activo y borrado.

## Decisions

- **Tablas**:
  - `sites`: `name`, único.
  - `assets`: `name` y `site_id` (FK), con nombre único dentro de cada sede.
  - `incidents`: columna nueva `asset_id`, FK *nullable*.
- **Incidencias anteriores**: `asset_id` es nullable en base de datos para conservar las incidencias que
  ya existen. La obligatoriedad se aplica en el formulario de alta. En el listado y la ficha se muestra
  «Sin activo». Alternativa descartada: asignar un activo genérico en la migración, porque falsea los datos.
- **Policies** `SitePolicy` y `AssetPolicy`: `viewAny`, `view`, `create` y `update` solo para administradores;
  `delete` siempre `false`. Filament oculta la navegación y deniega las páginas (403) a partir de la policy.
- **Recursos** `SiteResource` y `AssetResource` con páginas List, Create y Edit, agrupados en la navegación
  bajo «Catálogo». La tabla de activos muestra la sede y tiene filtro por sede.
- **Selector de activo en el alta**: `Select` con búsqueda y opciones agrupadas por sede
  (`[sede => [id => activo]]`) y la regla `exists:assets,id`. El activo no aparece en ningún formulario de
  edición porque las incidencias no se editan.
- **Filtro por sede** en el listado de incidencias: `SelectFilter` sobre la relación `asset.site`.
- **Seeder** `SiteSeeder` con Planta Valencia, Planta Sagunto y Almacén Paterna y varios activos en cada
  una, ejecutado antes de `IncidentSeeder`. Las incidencias de ejemplo indican su activo.
- **Decisiones sobre las notas del issue**: las incidencias previas quedan sin activo y el activo no se
  puede cambiar tras el alta.

## Risks / Trade-offs

- [`asset_id` nullable permite, a nivel de base de datos, incidencias nuevas sin activo] → La única vía
  de alta es el formulario, que lo exige y tiene test; se puede hacer NOT NULL más adelante cuando no
  queden incidencias antiguas.

## Migration Plan

Migraciones aditivas. Tras desplegar, las incidencias existentes muestran «Sin activo». Rollback con
`migrate:rollback`.
