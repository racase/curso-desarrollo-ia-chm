# Change: Add user management

## Why

Los administradores necesitan mantener las cuentas y roles que determinan el
acceso al panel y la responsabilidad sobre las incidencias. Actualmente los
usuarios solo se crean como datos iniciales, sin una interfaz administrativa ni
reglas que protejan la continuidad de las cuentas administradoras.

## What Changes

- Añadir una gestión de usuarios en Filament visible y accesible únicamente para
  administradores.
- Permitir listar, crear, editar y eliminar lógicamente usuarios con los roles
  administrador, técnico y solicitante.
- Exigir nombre, correo único, contraseña y rol al crear; al editar, permitir
  conservar la contraseña dejando su campo vacío.
- Impedir eliminar o degradar al último administrador activo.
- Conservar la autoría y las relaciones históricas de incidencias mediante
  eliminación lógica, mostrando también los usuarios eliminados en esos
  registros relacionados.

## Capabilities

### New Capabilities

- `user-management`: administración segura de cuentas y roles desde el panel,
  incluida la protección del último administrador y la conservación de la
  trazabilidad histórica.

### Modified Capabilities

Ninguna. Las capacidades de incidencias mantienen su comportamiento; la
eliminación lógica evita perder las relaciones y autorías que ya exigen.

## Impact

- Nuevos recurso, páginas, formularios y tabla de Filament para usuarios.
- Nueva policy y servicio de dominio para autorización y reglas de continuidad
  administrativa.
- Nueva migración para eliminación lógica de usuarios y actualización de sus
  relaciones Eloquent históricas.
- Nuevos tests de Feature para acceso, validación, edición, eliminación y
  preservación de relaciones.
