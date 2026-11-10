# Actividad de clase UT3: Acceso y autorización en la tienda de informática

## 1. Continuación del proyecto

**NEXIUM SOFTWARE FACTORY SL** quiere que cada cliente acceda a su propio espacio y que solo el personal autorizado gestione la tienda. Crea **`tienda3`** como una aplicación Laravel independiente basada en `tienda2`, con MySQL y la misma estructura MVC, presentación y navegación.

Este enunciado amplía el de la segunda entrega. Conserva el catálogo público, categorías, novedades, avisos, escaparate, fotografías, servicios de envío, montaje y financiación, administración, carrito, presupuestos y compra simulada. Mantén sus validaciones, cálculos con céntimos enteros, transacciones, instantáneas históricas, paginación de cinco registros y botones Volver. La portada de Tu Espacio seguirá sin botón Volver.

Las condiciones de la entrega anterior que permitían entrar sin identificarse, elegir cualquier cliente y consultar un histórico compartido quedan sustituidas por las siguientes reglas de acceso.

## 2. Registro y autenticación

- Ofrece un formulario de registro con nombre obligatorio de hasta 80 caracteres, correo válido y único de hasta 150 caracteres y contraseña confirmada de al menos ocho caracteres.
- Crea una cuenta en `users` y su ficha en `clientes` mediante una única transacción. Crea la ficha a través de la relación del usuario. No te apropies de clientes anteriores por coincidencia de correo.
- Guarda la contraseña con hash mediante el cast del modelo. El registro público asignará siempre el rol cliente, aunque se envíen campos de rol o propietario manipulados.
- Autentica la nueva cuenta y regenera la sesión después del registro.
- El acceso utilizará `Auth::attempt`, correo y contraseña, con la opción Recordarme. Ante un fallo muestra un mensaje genérico y conserva únicamente el correo. Tras acceder, regenera la sesión y recupera el destino solicitado con `intended`.
- Protege registro y acceso con `guest`. El nombre de la ruta de acceso será `login` para las redirecciones de `auth`.
- La salida requerirá POST, `auth` y CSRF. Ejecuta `Auth::logout`, invalida la sesión y regenera el token CSRF.
- Añade un perfil de consulta con nombre, correo y rol de la cuenta.

## 3. Roles y middleware

Define el enum de cadena `Rol` con los valores **cliente** y **gestor** (Admin), aplícalo como cast en User y guarda el rol en MySQL con cliente como valor predeterminado.

Registra en `bootstrap/app.php` un middleware con alias `gestor` que compruebe el enum del usuario y responda 403 si no tiene ese rol. Aplica `auth` y `gestor` a todo el grupo `/admin`, incluidos sus POST, PUT, PATCH y DELETE. El gestor mantendrá los CRUD existentes y podrá consultar todos los pedidos y cambiar únicamente su estado.

El catálogo y los contenidos de presentación seguirán siendo públicos. Tu Espacio, perfil, ficha, favoritos, carrito, presupuestos, pago e histórico personal requerirán `auth`. Los servicios de cálculo existentes podrán seguir consultándose públicamente.

En la cabecera, los visitantes verán únicamente Registrarse y Acceder. Los clientes autenticados verán Usuario (Tu Espacio), Carrito (su carrito propio), Perfil y Salir. El gestor verá Admin, Perfil y Salir, sin los accesos de cliente Usuario y Carrito. Las rutas estarán protegidas aunque se escriba la URL o se envíe una petición directamente.

## 4. Propiedad de los datos y policies

Relaciona User y Cliente uno a uno mediante `clientes.user_id`, único y anulable para conservar clientes anteriores sin inventar propietarios. Las nuevas cuentas tendrán su propia ficha. Si se elimina esa ficha desde administración, la cuenta podrá crear otra desde el formulario de ficha.

Elimina los selectores públicos de clientes y los campos de propietario de los formularios de compra. Obtén el cliente desde la cuenta autenticada, nunca desde `cliente_id` o `user_id` recibidos. La edición de la ficha conservará las reglas de nombre, tipo, edad, correo y dirección de la entrega anterior; el correo de la ficha será un dato comercial independiente del correo de acceso.

Implementa policies y ejecuta `Gate::authorize` antes de las operaciones sobre recursos concretos:

| Recurso | Permisos |
| --- | --- |
| Cliente | Su cuenta puede consultar y actualizar su ficha. La gestión general continúa en administración. |
| Carrito | Solo la cuenta del cliente puede verlo, añadir productos, generar presupuesto y comprar. |
| Presupuesto | Su propietario puede consultarlo. El gestor puede consultar un documento por su URL. |
| Pedido | Su propietario y el gestor pueden consultarlo. Solo el gestor puede actualizar su estado desde administración. |

Los listados personales se consultarán desde las relaciones del usuario. Un identificador ajeno existente en la URL de una ficha o documento producirá 403. Un identificador inexistente producirá 404. Ocultar un enlace en Blade no sustituye a la autorización del servidor.

Guarda también `user_id` en los nuevos pedidos y presupuestos a partir del propietario real del carrito. Así la cuenta conservará acceso a sus documentos aunque se elimine la ficha comercial. Los documentos anteriores sin propietario no aparecerán en listados personales y solo podrán ser consultados por el gestor. No asignes documentos antiguos a cuentas por coincidencias de nombre o correo.

Mantén las instantáneas, el precio aplicado al añadir unidades, la conservación del carrito al presupuestar, su vaciado al comprar y la reversión completa si falla una transacción. No se podrán alterar propietarios, precios, cantidades históricas o datos del pedido mediante campos adicionales.

## 5. Preferencia visual con cookie

Al principio del contenido de la página principal (`/`), permite elegir un tema claro u oscuro y volver a la opción predeterminada. Muestra estos controles únicamente en esa página. Valida los dos valores permitidos, guarda una cookie durante treinta días y léela en el layout común para aplicar el tema a toda la tienda. La opción predeterminada será claro. Al restablecer, elimina la cookie. No guardes credenciales ni datos personales en ella.

## 6. Favoritos de productos en sesión

Desde el catálogo y la ficha de producto, permite a una cuenta añadir y quitar favoritos y consultar su selección en `/favoritos`. Guarda únicamente un array de identificadores en sesión, sin duplicados. Permite vaciar la selección completa y comprueba que el producto existe al añadirlo o quitarlo.

Los favoritos no modificarán el carrito ni se guardarán como datos permanentes. Dos sesiones no compartirán favoritos; cerrar sesión eliminará esta selección temporal. Si un producto deja de existir, la lista mostrará los que todavía existan en la base de datos.

## 7. Persistencia e instalación

Incluye datos de catálogo y métodos de pago y cuentas ficticias de demostración para un gestor y dos clientes con fichas diferentes. Documenta sus datos de acceso. Los seeders no duplicarán estas cuentas al ejecutarse de nuevo. No incluyas credenciales personales, dependencias instaladas, archivos subidos, sesiones ni cachés en la entrega.

Comprueba registro y hash, correo duplicado, credenciales incorrectas, Recordarme, regeneración de sesión y salida por POST. Verifica que un visitante es enviado al acceso, que un cliente recibe 403 en administración y que el gestor puede gestionarla.

Usa dos cuentas para demostrar que sus fichas, carritos y documentos permanecen separados. Prueba URLs ajenas y campos de propietario manipulados, tanto en consulta como en escritura. Comprueba también los favoritos sin duplicados, su aislamiento y su limpieza al salir, y la creación y eliminación de la cookie de preferencia.

Recorre la compra completa con una cuenta real de prueba: añade unidades, genera presupuesto, cambia el precio del catálogo, compra y consulta los documentos guardados. Repite el pago sobre el carrito vacío y provoca un fallo controlado para comprobar la reversión. Conserva las comprobaciones de los servicios de cálculo y verifica la navegación y la administración heredadas.
