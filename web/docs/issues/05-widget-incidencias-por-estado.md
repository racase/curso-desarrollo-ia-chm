---
title: Resumen de incidencias por estado en el panel
labels: [feature, web]
depends_on: [1]
---

## Contexto

Al entrar al panel no hay ninguna visión de conjunto: para saber cuánto trabajo hay
pendiente hay que abrir el listado y filtrar estado por estado. Este issue añade un
resumen en la página de inicio del panel.

## Historia de usuario

- Como técnico, quiero ver al entrar cuántas incidencias hay en cada estado, para saber
  la carga de trabajo de un vistazo.
- Como solicitante, quiero ver cuántas de mis incidencias siguen pendientes, para saber
  cómo van.

## Alcance

- Resumen en la página de inicio del panel con el número de incidencias en cada uno de
  los cuatro estados.
- El resumen respeta la visibilidad del issue 1: técnicos y administradores cuentan todas
  las incidencias; un solicitante, solo las suyas.
- Los estados sin incidencias se muestran con un cero.

## Fuera de alcance

- Gráficas y evolución en el tiempo.
- Desglose por prioridad, técnico o sede.
- Exportar los datos.
- Actualización automática sin recargar la página.

## Criterios de aceptación

1. Dado que existen tres incidencias abiertas, dos en curso y una resuelta, cuando Tomás
   Técnico abre el inicio del panel, entonces ve 3 en «abierta», 2 en «en curso»,
   1 en «resuelta» y 0 en «cerrada».
2. Dado que de esas incidencias solo una abierta es de Sara, cuando Sara Solicitante abre
   el inicio del panel, entonces ve 1 en «abierta» y 0 en el resto.
3. Dado que no existe ninguna incidencia, cuando cualquier usuario abre el inicio del panel,
   entonces ve los cuatro estados con un cero.

## Notas

- ¿Debe cada cifra enlazar al listado ya filtrado por ese estado?
- ¿Se quiere destacar de alguna forma las incidencias de prioridad crítica?
