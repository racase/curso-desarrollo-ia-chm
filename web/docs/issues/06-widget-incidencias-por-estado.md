---
title: Widget de incidencias por estado
labels: [feature, web]
depends_on: [1]
---

## Resumen

El panel necesita ofrecer una lectura rápida del volumen de incidencias abiertas
según su estado. Un widget permitirá conocer la situación general sin tener que
revisar primero el listado completo.

## Problema y oportunidad

El listado permite consultar casos individuales, pero no resume de forma
inmediata cómo se distribuye el trabajo abierto. Mostrar los recuentos por estado
ayudará a detectar dónde se concentra la actividad.

## Objetivos

- Mostrar en el panel el número de incidencias abiertas por estado.
- Facilitar una comprensión rápida de la situación de las incidencias.
- Mantener los recuentos coherentes con la información disponible en el producto.

## Usuarios afectados

- Los usuarios del panel necesitan consultar un resumen de las incidencias que
  les corresponda ver según su rol.

## Alcance

- Widget visible en el panel.
- Recuento de incidencias abiertas agrupadas por estado.
- Actualización del resumen a partir de las incidencias existentes.

## Fuera de alcance

- Gráficos históricos o análisis de tendencias.
- Indicadores por prioridad, técnico, sede o activo.
- Objetivos de servicio o tiempos de resolución.
- Navegación o acciones adicionales desde el widget.

## Requisitos de producto

- El widget debe mostrar cuántas incidencias abiertas hay en cada estado
  relevante.
- Los recuentos deben coincidir con las incidencias registradas.
- La información mostrada debe respetar las reglas de visibilidad definidas en el
  PRD 1.
- El resumen debe estar disponible desde el panel de InfraTicket.

## Criterios de éxito

- Los usuarios pueden conocer desde el panel la distribución actual de las
  incidencias abiertas.
- Los recuentos del widget son coherentes con las incidencias que cada usuario
  puede consultar.
- El resumen se entiende sin necesidad de acceder al listado completo.

## Riesgos y preguntas abiertas

- Debe concretarse qué estados se consideran abiertos a efectos del widget.
- Debe decidirse si el widget estará disponible para los tres roles.
- Queda por definir cómo se presenta el recuento cuando un estado no contiene
  incidencias.
