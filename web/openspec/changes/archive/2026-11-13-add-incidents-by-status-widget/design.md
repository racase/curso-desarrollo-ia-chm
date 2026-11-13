# Design

## Context

El panel usa el `Dashboard` por defecto de Filament con `AccountWidget` y descubre widgets en
`app/Filament/Widgets`. La visibilidad por rol ya está en el scope `Incident::visibleTo(User)`, que usa el
recurso de incidencias. Ver `proposal.md`.

## Goals / Non-Goals

**Goals:**
- Reutilizar `visibleTo` para que el resumen y el listado cuenten exactamente lo mismo.

**Non-Goals:**
- Gráficas, desglose por prioridad, técnico o sede, exportación y refresco automático.

## Decisions

- **`StatsOverviewWidget`** de Filament (`IncidentsByStatusOverview`), con un `Stat` por cada caso de
  `IncidentStatus` en el orden del enum. Las cifras salen de una única consulta agrupada
  (`visibleTo($user)->groupBy('status')`), y los estados ausentes se rellenan con cero.
- **Enlace** con `IncidentResource::getUrl('index', ['filters' => ['status' => ['value' => …]]])`: la página
  de listado lee los filtros de la query string `filters`.
- **Sin polling**: se desactiva el refresco periódico que Filament activa por defecto en los widgets
  de estadísticas, porque el issue lo deja fuera de alcance.
- **Orden**: el widget va delante de `AccountWidget` (`$sort = -1`).
- **Decisiones sobre las notas del issue**: cada cifra enlaza al listado filtrado; no se destaca la
  prioridad crítica, porque el desglose por prioridad está fuera de alcance.

## Risks / Trade-offs

- [El nombre del parámetro `filters` depende de Filament] → Lo cubre un test que abre la URL y comprueba el filtro aplicado.
