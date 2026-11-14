# Tasks

## 1. Persistencia y trazabilidad

- [x] 1.1 Generar una migración nueva para `deleted_at`, añadir `SoftDeletes` a `User` y verificar con un test de Feature que una cuenta eliminada queda excluida de las consultas activas.
- [x] 1.2 Incluir usuarios eliminados en las relaciones de solicitante, técnico asignado, autor de comentario y autor de cambio de estado, y verificar con tests que sus nombres siguen accesibles después del borrado lógico.

## 2. Reglas de dominio y autorización

- [x] 2.1 Generar `UserPolicy` con permisos exclusivos para administradores y verificar con tests de Feature que técnicos y solicitantes no pueden listar, crear, actualizar ni eliminar usuarios.
- [x] 2.2 Implementar un servicio transaccional de creación, actualización y eliminación de usuarios y verificar con tests que aplica los datos válidos y conserva la contraseña cuando no recibe una nueva.
- [x] 2.3 Proteger en el servicio la eliminación y degradación del último administrador activo, y verificar con tests los rechazos y el caso permitido cuando existe otro administrador.

## 3. Gestión de usuarios en Filament

- [x] 3.1 Generar el recurso y las páginas Filament de listado, alta y edición, y verificar con tests que solo un administrador puede abrirlas y que la navegación no aparece a los otros roles.
- [x] 3.2 Configurar el listado con nombre, correo y etiqueta española del rol, sin borrado masivo, y verificar con un test de componente que muestra usuarios activos y oculta cuentas eliminadas.
- [x] 3.3 Configurar el formulario de alta con nombre, correo único, contraseña confirmada y los tres roles admitidos, y verificar con tests de componente el alta correcta, los campos obligatorios, el correo duplicado y el rol inválido.
- [x] 3.4 Configurar la edición con contraseña opcional y la acción individual de borrado mediante el servicio, y verificar con tests de componente la edición, el cambio de contraseña, el borrado lógico y los errores del último administrador.

## 4. Verificación integral

- [x] 4.1 Ejecutar `docker compose exec app vendor/bin/pint --test` y verificar que no quedan errores de estilo.
- [x] 4.2 Ejecutar `docker compose exec app php artisan test` y verificar que toda la suite queda en verde.
- [x] 4.3 Ejecutar la verificación estricta de OpenSpec y revisar que cada requisito y escenario de `user-management` tiene evidencia de implementación y tests.

## Workflow follow-up

- Sincronizar la especificación delta con las especificaciones principales.
- Archivar el cambio después de superar la verificación del proyecto.
- Crear un commit Conventional Commits que referencie el issue #4 y subirlo a `feat/poc_codex`.
