# .agents — skills compartidas de InfraData

Las skills de este proyecto van en `.agents/skills/<nombre>/SKILL.md`.
Esta ruta la leen Codex y OpenCode, así que una skill escrita aquí sirve para ambos.

Cada skill es una carpeta con un `SKILL.md` que empieza con `name` y `description`:

```markdown
---
name: nombre-de-la-skill
description: Cuándo debe usarla el agente, en una frase.
---

Instrucciones paso a paso.
```

Las instrucciones generales del proyecto no van aquí, sino en `../AGENTS.md`.
