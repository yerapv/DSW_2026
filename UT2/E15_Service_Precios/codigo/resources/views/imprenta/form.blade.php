<!doctype html>
<html lang="es">

<body>
    <h1>Presupuesto imprenta</h1>
    <form method="POST" action="/imprenta">@csrf<input type="number" name="copias" min="1"><label><input
                type="checkbox" name="color" value="1"> Color</label><label><input type="checkbox"
                name="encuadernado" value="1"> Encuadernado</label><button>Calcular</button></form>
</body>

</html>
