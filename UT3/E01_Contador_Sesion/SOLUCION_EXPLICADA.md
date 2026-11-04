# Solución E01 Contador por sesión

La sesión permite asociar datos del servidor con un cliente concreto a través del identificador de sesión que viaja en una cookie.

- El dato sobrevive entre peticiones.
- No se usa una variable global.
- Reiniciar afecta solo a ese cliente.

# No se debe confundir
- Confundir sesión con una variable PHP persistente.
- Guardar el contador en BD cuando no se necesita persistencia duradera.
