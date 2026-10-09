# Guía — Análisis con ChatGPT antes de Codex

## Papel de cada herramienta

```text
CHATGPT
=
analista funcional y revisor

CODEX
=
agente que trabaja sobre el repositorio
```

## Flujo

```text
ENUNCIADO
   ↓
CHATGPT
   ↓
requisitos + ambigüedades + criterios
   ↓
DECISIÓN HUMANA
   ↓
CODEX
   ↓
análisis del repositorio
   ↓
PLAN TÉCNICO
   ↓
CHATGPT
   ↓
revisión del plan
   ↓
DECISIÓN HUMANA
   ↓
CODEX
   ↓
implementación + tests
   ↓
CHATGPT
   ↓
auditoría final con evidencia
```

## Fase 1 — Requisitos

Usar:

`prompts/chatgpt/00_ANALISIS_REQUISITOS.md`

El alumno debe decidir qué propuestas acepta.

## Fase 2 — Modelo conceptual

Usar:

`prompts/chatgpt/01_MODELO_CONCEPTUAL.md`

No aceptar automáticamente nuevas entidades.

## Fase 3 — Criterios de aceptación

Usar:

`prompts/chatgpt/02_CASOS_ACEPTACION.md`

Se convertirán después en pruebas.

## Fase 4 — Pasar a Codex

Copiar al proyecto:

- `AGENTS.md`
- `.agents/`

Abrir VS Code y ejecutar los prompts Codex en orden.

## Fase 5 — Revisar el plan de Codex con ChatGPT

Copiar únicamente el plan técnico, sin secretos.

Usar:

`prompts/chatgpt/03_REVISAR_PLAN_CODEX.md`

## Fase 6 — Implementación

El alumno aprueba el plan.

Codex implementa por fases.

## Fase 7 — Auditoría final

Recoger:

- resumen de Codex;
- resultados de tests;
- `git diff --stat`.

Usar:

`prompts/chatgpt/04_REVISAR_RESULTADO.md`

ChatGPT debe distinguir lo verificado de lo no verificado.

## Fase 8 — Reflexión

Usar:

`prompts/chatgpt/05_REFLEXION.md`

## Seguridad

Nunca pegar en ChatGPT:

- `.env`;
- contraseñas;
- tokens;
- claves API;
- credenciales;
- datos personales reales.
