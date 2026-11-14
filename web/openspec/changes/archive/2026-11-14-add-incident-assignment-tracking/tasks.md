# Tasks

## 1. Persistencia y flujo de seguimiento

- [x] 1.1 Generar el modelo, migración y factory del historial de estados, añadir
  la relación opcional de técnico asignado mediante una migración nueva y
  verificar con pruebas Feature las relaciones, casts e integridad referencial.
- [x] 1.2 Implementar el siguiente estado permitido en el enum y un servicio
  transaccional para asignar técnicos y avanzar estados, y verificar con pruebas
  Feature asignaciones válidas, rechazo de no técnicos, flujo completo, saltos,
  retrocesos y ausencia de historial ante errores.

## 2. Autorización

- [x] 2.1 Ampliar la policy con permisos específicos de asignación y transición,
  manteniendo denegada la edición general, y verificar que solo administradores
  asignan y que solo administradores o el técnico asignado avanzan el estado.

## 3. Seguimiento en el panel

- [x] 3.1 Añadir a la ficha una acción de asignación visible para administradores,
  limitada a usuarios técnicos, y verificar con pruebas Livewire la asignación,
  reasignación, visibilidad y denegación para los demás roles.
- [x] 3.2 Añadir una acción que avance únicamente al siguiente estado permitido y
  verificar con pruebas Livewire el flujo completa, las autorizaciones y la
  desaparición de la acción cuando la incidencia está cerrada.
- [x] 3.3 Mostrar el técnico responsable y el historial cronológico en la ficha,
  conservando ausentes las acciones de edición general y eliminación, y
  verificar la información visible para cada rol con acceso a la incidencia.

## 4. Verificación integrada

- [x] 4.1 Ejecutar `docker compose exec app vendor/bin/pint --test` y
  `docker compose exec app php artisan test`, y verificar que estilo y suite
  completa terminan sin errores.

## Workflow follow-up

- Verificar la implementación contra los artefactos de este cambio.
- Sincronizar ambas delta specs y archivar el cambio cuando la verificación sea
  satisfactoria.
- Crear un commit Conventional Commits del issue #2 y publicarlo en
  `origin/feat/poc_codex`.
