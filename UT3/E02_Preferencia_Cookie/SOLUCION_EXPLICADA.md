# Solución E02 — Preferencia con cookie

Una cookie es información almacenada en el cliente y enviada posteriormente al servidor. Es apropiada para preferencias no sensibles.

- Se lee con `$request->cookie`.
- La cookie tiene duración limitada.
- Se puede eliminar.

# Errores frecuentes
- Guardar contraseñas o secretos.
- Confundir “cookie” con “sesión”.
