# InfraTicket — demo web

Aplicación Laravel + Filament + PostgreSQL que usaremos como base de la demo web.
Ahora mismo solo tiene el acceso al panel y unos usuarios de prueba: las incidencias y el
resto de funcionalidad las construiremos en clase con OpenSpec y Codex.

## Requisitos

- Docker Desktop (o Docker dentro de WSL2) o Podman

Para la aplicación, nada más: PHP y Composer van dentro del contenedor y no hace falta
instalarlos en tu equipo. Node tampoco lo necesita la aplicación, pero sí Codex y OpenSpec
(ver el `README.md` de la raíz).

Con Podman, sustituye `docker` por `podman` en todos los comandos de este documento.

## Arranque

```bash
cd web
docker compose up -d --build
docker compose logs -f app     # espera a ver "InfraTicket ready"; Ctrl+C para salir
```

La primera vez tarda unos minutos: construye la imagen, instala las dependencias, crea el
`.env`, migra la base de datos y carga los usuarios. Las siguientes veces arranca en segundos.

Abre <http://localhost:8000/admin> e inicia sesión con uno de los usuarios de prueba.

Las dependencias de Composer se guardan en un volumen de Docker, no en la carpeta `vendor/` de tu
equipo, que quedará vacía. Para consultar el código de una librería:
`docker compose exec app cat vendor/<paquete>/<fichero>`.

## Usuarios de prueba

Todos con la contraseña `password`.

| Rol | Nombre | Correo |
|---|---|---|
| Administrador | Ana Admin | `admin@infraticket.test` |
| Técnico | Tomás Técnico | `tecnico1@infraticket.test` |
| Técnico | Teresa Técnica | `tecnico2@infraticket.test` |
| Solicitante | Sara Solicitante | `solicitante1@infraticket.test` |
| Solicitante | Sergio Solicitante | `solicitante2@infraticket.test` |

De momento los tres roles ven lo mismo: un panel vacío.

## Comandos habituales

Los comandos de PHP se ejecutan dentro del contenedor `app`:

| Para | Comando |
|---|---|
| Tests | `docker compose exec app php artisan test` |
| Estilo de código | `docker compose exec app vendor/bin/pint` |
| Cualquier comando de Artisan | `docker compose exec app php artisan ...` |
| Añadir un paquete | `docker compose exec app composer require ...` |
| Recrear la base de datos con los usuarios | `docker compose exec app php artisan migrate:fresh --seed` |
| Consola SQL | `docker compose exec db psql -U infraticket` |
| Ver el log del servidor | `docker compose logs -f app` |
| Parar todo | `docker compose down` |
| Reinstalar dependencias desde cero | `docker compose down`, `docker volume rm web_infraticket-vendor` y volver a arrancar |

Los tests usan SQLite en memoria, así que no tocan los datos de PostgreSQL.

### Alternativa: PHP instalado en tu equipo

Si ya tienes PHP 8.3+ (con `pdo_pgsql`, `pdo_sqlite` e `intl`) y Composer, puedes levantar solo
la base de datos y ejecutar la aplicación directamente:

```bash
docker compose up -d db
composer setup
php artisan serve
```

En ese caso los comandos de la tabla se usan sin el prefijo `docker compose exec app`.

## Qué hay y qué no

Hay:

- Panel de Filament en `/admin` con inicio de sesión.
- Modelo `User` con un rol (`App\Enums\UserRole`): administrador, técnico o solicitante.
- Usuarios de prueba (`database/seeders/DatabaseSeeder.php`).
- Tests del inicio de sesión (`tests/Feature/AdminLoginTest.php`).

No hay, a propósito:

- Incidencias ni ningún otro modelo de negocio.
- Gestión de usuarios desde el panel.
- Permisos por rol.
- OpenSpec: lo inicializaremos en clase con `openspec init`.

## Configuración de agentes

| Ruta | Lo usa | Contenido |
|---|---|---|
| `AGENTS.md` | Codex y OpenCode | Instrucciones del proyecto: mapa, comandos, convenciones |
| `.agents/skills/` | Codex y OpenCode | Skills del proyecto (vacío de momento) |
| `.codex/config.toml` | Codex | Ajustes de Codex (sin ajustes activos de momento) |
| `.claude/` | Claude Code | `CLAUDE.md`, que importa los `AGENTS.md`, y `settings.json` |
| `.opencode/opencode.json` | OpenCode | Ajustes de OpenCode |

El curso se hace con Codex. Ábrelo desde esta carpeta y, la primera vez, acepta confiar en
ella: si no, Codex ignora `.codex/`.

## Estructura

```
web/
├── AGENTS.md              instrucciones para los agentes
├── .agents/skills/        skills del proyecto
├── .codex/                ajustes de Codex
├── .claude/               ajustes de Claude Code
├── .opencode/             ajustes de OpenCode
├── app/
│   ├── Enums/UserRole.php
│   ├── Models/User.php
│   └── Providers/Filament/AdminPanelProvider.php   configuración del panel
├── database/
│   ├── migrations/
│   ├── factories/
│   └── seeders/
├── tests/
├── docker/start.sh        arranque del contenedor
├── Dockerfile
├── docker-compose.yml
└── .env.example
```

Los recursos, páginas y widgets de Filament que se creen irán en `app/Filament/`.
