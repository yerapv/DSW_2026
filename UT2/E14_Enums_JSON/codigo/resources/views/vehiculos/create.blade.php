<!doctype html>
<html lang="es">

<body>
    <h1>Nuevo vehículo</h1>
    <form method="POST" action="/vehiculos">@csrf<input name="nombre" placeholder="Nombre"><input name="matricula"
            placeholder="Matrícula"><select name="tipo">
            <option value="coche">Coche</option>
            <option value="moto">Moto</option>
            <option value="furgoneta">Furgoneta</option>
        </select><input type="number" name="plazas" placeholder="Plazas"><input type="number" name="cilindrada"
            placeholder="Cilindrada"><button>Guardar</button></form>
</body>

</html>
