<!doctype html>
<html lang="es">

<body>
    <h1>Inscripción</h1>
    <form method="POST" action="/inscripcion">@csrf
        <label>Nombre <input name="nombre"></label><br>
        <label>Email <input name="email" type="email"></label><br>
        <label>Edad <input name="edad" type="number"></label><br>
        <label>Nivel <select name="nivel">
                <option value="inicial">Inicial</option>
                <option value="medio">Medio</option>
            </select></label><br>
        <button>Enviar</button>
    </form>
</body>

</html>