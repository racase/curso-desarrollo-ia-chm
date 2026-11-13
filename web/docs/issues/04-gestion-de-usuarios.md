---
title: Gestión de usuarios
labels: [feature, web]
depends_on: []
---

## Resumen

Los administradores necesitan gestionar desde el panel las personas que acceden
a InfraTicket y asignarles uno de los roles existentes. Esta capacidad permitirá
mantener usuarios y responsabilidades sin depender de los datos de prueba.

## Problema y oportunidad

Actualmente existen usuarios precargados, pero el panel no permite consultar ni
mantener sus datos. Incorporar una gestión centralizada hará posible adaptar el
acceso a las personas que utilicen la aplicación.

## Objetivos

- Permitir que los administradores mantengan el conjunto de usuarios.
- Asegurar que cada usuario tenga asignado un rol reconocido por el producto.
- Impedir que técnicos y solicitantes accedan a esta gestión.

## Usuarios afectados

- Los administradores, como Ana Admin, necesitan listar, crear, editar y eliminar
  usuarios, además de asignarles un rol.
- Técnicos y solicitantes quedan afectados por los datos y roles que se les
  asignen, pero no deben acceder a las funciones de gestión.

## Alcance

- Listado de usuarios desde el panel.
- Alta, edición y eliminación de usuarios.
- Asignación de los roles administrador, técnico o solicitante.
- Acceso a la gestión reservado a administradores.

## Fuera de alcance

- Creación de roles adicionales o permisos personalizados.
- Gestión de incidencias asociadas a cada usuario.
- Edición del perfil por parte del propio usuario.
- Procesos de invitación o recuperación de acceso.

## Requisitos de producto

- Los administradores deben poder consultar el listado de usuarios.
- Los administradores deben poder crear, editar y eliminar usuarios.
- Cada usuario debe tener asignado uno de los tres roles existentes.
- Técnicos y solicitantes no deben poder acceder a la gestión de usuarios.

## Criterios de éxito

- Ana Admin puede mantener usuarios y asignarles el rol adecuado desde el panel.
- Tomás Técnico, Teresa Técnica, Sara Solicitante y Sergio Solicitante no pueden
  acceder a las funciones de gestión de usuarios.
- El listado permite reconocer a los usuarios y el rol que tienen asignado.

## Riesgos y preguntas abiertas

- Debe definirse qué ocurre al eliminar un usuario relacionado con información
  del producto.
- Debe decidirse cómo evitar que un administrador deje el sistema sin ningún
  usuario administrador.
- Queda por concretar qué datos son obligatorios al crear o editar un usuario.
