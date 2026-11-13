# Tasks

## 1. Dominio de incidencias

- [x] 1.1 Generar el modelo, la migración y la factory de incidencias, añadir los
  enums de estado y prioridad, y verificar con una prueba Feature que los datos,
  casts y relaciones se persisten correctamente en SQLite.
- [x] 1.2 Completar los estados de la factory para solicitante, estado y prioridad,
  y verificar que las pruebas pueden crear incidencias válidas con cada valor
  permitido.

## 2. Autorización y visibilidad

- [x] 2.1 Implementar la policy de incidencias y su alcance de consulta para
  centralizar permisos por rol y propiedad, y verificar con pruebas Feature que
  solicitantes solo consultan registros propios mientras técnicos y
  administradores consultan todos.
- [x] 2.2 Bloquear creación para técnicos y administradores, además de edición y
  eliminación para todos los roles, y verificar las denegaciones mediante las
  rutas o páginas del recurso.

## 3. Experiencia en el panel

- [x] 3.1 Generar el recurso de Filament con páginas de listado, alta y detalle,
  configurar etiquetas en español y verificar con pruebas Livewire que un
  solicitante crea una incidencia propia en estado abierta con los datos
  obligatorios.
- [x] 3.2 Configurar las columnas y los filtros por estado y prioridad, y verificar
  con pruebas Livewire que cada filtro y su combinación devuelven únicamente las
  incidencias visibles que corresponden.
- [x] 3.3 Retirar acciones y rutas de edición o eliminación del recurso, y verificar
  que la interfaz y la autorización mantienen las incidencias como solo lectura
  después del alta.

## 4. Verificación integrada

- [x] 4.1 Ejecutar `docker compose exec app vendor/bin/pint --test` y
  `docker compose exec app php artisan test`, y verificar que estilo y suite
  completa terminan sin errores.

## Workflow follow-up

- Verificar la implementación contra los artefactos de este cambio.
- Sincronizar la especificación y archivar el cambio cuando la verificación sea
  satisfactoria.
- Crear un commit Conventional Commits del issue #1 y publicarlo en
  `origin/feat/poc_codex`.
