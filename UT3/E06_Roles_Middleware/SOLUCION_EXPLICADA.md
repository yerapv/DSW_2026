# Solución E06 — Roles y middleware

La autenticación responde quién es el usuario; el middleware de rol decide si una clase general de usuarios puede entrar en una zona.

- Rol casteado a Enum.
- Middleware registrado en bootstrap/app.php.
- Seguridad aplicada a ruta.

# Errores frecuentes
- Comprobar rol solo en Blade.
- Guardar rol como texto disperso en muchos if.

