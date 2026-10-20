<!doctype html>
<html lang="es">

<body>
    <h1>Nueva pieza</h1>
    <form method="POST" action="/piezas" enctype="multipart/form-data">@csrf<input name="nombre"><input
            name="autor"><input type="file" name="imagen" accept="image/*"><button>Guardar</button></form>
</body>

</html>
