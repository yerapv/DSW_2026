# Práctica inicial guiada — Tienda de informática con Laravel y agentes de IA

## Objetivo

Desarrollar un listado de productos de una tienda de informática utilizando:

- ChatGPT para análisis funcional y revisión.
- Codex en VS Code para analizar y modificar el repositorio.
- `AGENTS.md` para reglas permanentes.
- Skills para procedimientos reutilizables.
- Git para controlar cambios.
- Tests para verificar el resultado.
- MCP y Engram de forma opcional para documentación y memoria persistente.

## Resultado funcional

La aplicación deberá exponer:

`GET /productos`

y permitir:

- listar productos;
- buscar por nombre o SKU mediante `?q=`;
- filtrar por categoría mediante `?categoria=`;
- mostrar SKU, nombre, categoría, precio, stock y estado;
- mostrar "No se encontraron productos" cuando proceda.

## Flujo de trabajo obligatorio

1. Análisis funcional con ChatGPT.
2. Revisión del análisis por el alumno.
3. Preparación de `AGENTS.md`.
4. Análisis del repositorio con Codex.
5. Diseño del modelo de datos.
6. Plan de implementación.
7. Revisión del plan con ChatGPT.
8. Implementación por fases con Codex.
9. Tests y verificación.
10. Revisión final con ChatGPT.
11. Memoria con Engram, opcional.

## Importante

No se deben compartir:

- `.env`;
- contraseñas;
- tokens;
- claves API;
- datos personales;
- credenciales de servicios.
