# Solución E07 — Notas privadas

La Policy centraliza decisiones de autorización sobre instancias concretas.

- Index filtra por user.
- Store crea mediante relación del usuario.
- Policy comprueba ownership.

# Errores frecuentes
- Confundir 404 con 403 de autorización.
- Añadir `user_id` desde formulario y confiar en él.