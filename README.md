# Curso de desarrollo con IA — CHM

Repositorio de trabajo del curso «Desarrollo con IA: de escribir prompts al desarrollo con agentes».
Contiene los dos proyectos de demostración sobre los que practicaremos el ciclo de
Spec-Driven Development con OpenSpec y Codex.

| Carpeta | Proyecto | Stack | Estado inicial |
|---|---|---|---|
| `web/` | InfraTicket, gestor de incidencias | Laravel, Filament, PostgreSQL | Login y usuarios de prueba |
| `datos/` | InfraData, analítica de sensores | Python, PostgreSQL + TimescaleDB | Lecturas de sensores cargadas, sin analítica |

Los dos proyectos llegan casi vacíos a propósito: la funcionalidad la construiremos en clase,
una feature cada vez.

## Antes de empezar

Necesitas en tu equipo:

- Git.
- Node.js 22.12 o superior: lo usan Codex CLI y OpenSpec, no los proyectos.
- Codex CLI: `npm install -g @openai/codex`, y después `codex login`.
- OpenSpec: `npm install -g @fission-ai/openspec@latest`.
- Un motor de contenedores con compose: Docker Desktop o Podman.

Cada proyecto añade sus propios requisitos en su `README.md`.

## Cómo empezar

1. Crea tu copia del repositorio como te indiquemos en clase y clónala.
2. Sigue el `README.md` de la carpeta con la que vayas a trabajar.
3. Abre el agente **dentro de esa carpeta** (`cd web` o `cd datos`), no en la raíz:
   cada proyecto tiene su propio `AGENTS.md` y su propia configuración de agentes.
4. La primera vez, Codex pregunta si confías en la carpeta. Acepta: si no, ignora la
   configuración del proyecto que hay en `.codex/`.

OpenSpec está instalado en tu equipo, pero no inicializado en los proyectos. Lo haremos
juntos en clase con `openspec init`, una vez dentro de cada carpeta.

## Configuración de agentes

El curso se hace con **Codex**. Cada proyecto incluye además la configuración base para
Claude Code y OpenCode, por si quieres repetir el ejercicio con otro agente.

| Dentro de `web/` y de `datos/` | Lo usa | Para qué |
|---|---|---|
| `AGENTS.md` | Codex y OpenCode | Instrucciones del proyecto. Es la fuente común |
| `.agents/skills/` | Codex y OpenCode | Skills del proyecto |
| `.codex/config.toml` | Codex | Ajustes de Codex para el proyecto |
| `.claude/` | Claude Code | `CLAUDE.md`, que importa los `AGENTS.md`, y `settings.json` |
| `.opencode/opencode.json` | OpenCode | Ajustes de OpenCode para el proyecto |

Las instrucciones se escriben una sola vez, en `AGENTS.md`. No las copies en las carpetas
de cada agente.

## Normas

- No subas ficheros `.env` ni credenciales reales.
- Trabaja siempre en una rama, nunca directamente en `main`.