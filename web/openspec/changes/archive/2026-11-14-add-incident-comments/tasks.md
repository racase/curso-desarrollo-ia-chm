# Tasks

## 1. Persistencia de comentarios

- [x] 1.1 Generar el modelo, migración y factory de comentarios, añadir las
  relaciones con incidencia y autor y verificar con pruebas Feature los datos,
  timestamps, orden estable y reglas de borrado referencial.

## 2. Publicación y autorización

- [x] 2.1 Ampliar la policy de incidencia con el permiso de comentar e
  implementar un servicio que autorice, rechace contenido vacío y asigne el
  autor autenticado; verificar con pruebas Feature solicitante propietario,
  técnico, administrador, solicitante ajeno y ausencia de suplantación.
- [x] 2.2 Verificar con pruebas Feature que los usuarios autorizados pueden
  comentar en todos los estados, incluido cerrado, sin modificar el estado de la
  incidencia ni habilitar edición o eliminación de comentarios.

## 3. Conversación en el panel

- [x] 3.1 Añadir a la ficha una acción modal de comentario con contenido
  obligatorio y verificar con pruebas Livewire publicación, validación,
  visibilidad por rol, autoría automática y uso en incidencias cerradas.
- [x] 3.2 Mostrar en la ficha la conversación de solo lectura con contenido,
  autor y fecha en orden cronológico estable, y verificar con pruebas Livewire
  que cada rol autorizado la consulta y un solicitante ajeno no puede verla.

## 4. Verificación integrada

- [x] 4.1 Ejecutar `docker compose exec app vendor/bin/pint --test` y
  `docker compose exec app php artisan test`, y verificar que estilo y suite
  completa terminan sin errores.

## Workflow follow-up

- Verificar la implementación contra los artefactos de este cambio.
- Sincronizar la delta spec y archivar el cambio cuando la verificación sea
  satisfactoria.
- Crear un commit Conventional Commits del issue #3 y publicarlo en
  `origin/feat/poc_codex`.
