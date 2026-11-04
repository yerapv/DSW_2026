# Solución E05 — Login y logout

Laravel autentica al usuario y guarda esa identidad en la sesión. `auth` comprueba identidad; no decide si el usuario puede modificar cualquier recurso.

- `guest` en login.
- `auth` en perfil/logout.
- Mensaje genérico en credenciales incorrectas.

# Errores frecuentes
- Consultar contraseña manualmente sin Hash/Auth.
- Proteger solo la vista y no la ruta.
- Logout por GET.

