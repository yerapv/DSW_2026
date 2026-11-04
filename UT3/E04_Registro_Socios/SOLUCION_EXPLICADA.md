# Solución E04 — Registro de socios

## Idea central
El registro crea una identidad persistente y después establece una sesión autenticada. Son dos operaciones distintas.

- Email único.
- Password confirmada y mínimo 8.
- Sesión regenerada tras autenticar.

# Errores frecuentes
- Guardar password sin hash.
- Confundir registro con login.
- No validar email único.