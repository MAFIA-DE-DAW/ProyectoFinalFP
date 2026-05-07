<!DOCTYPE html>
<html>

<head>

    <title>Crear Mascota</title>

    <link rel="stylesheet" href="<?= ASSETS_URL ?>/css/styles.css">

</head>

<body class="login">

    <div class="form-container">

        <h2>Crea tu EcoMascota 🌱</h2>

        <form method="POST">

            <label>Nombre de tu mascota</label>
            <input type="text" name="nombre" required>

            <label>Tipo de mascota</label>

            <select name="tipo">

                <option value="planta">🌱 Planta</option>
                <option value="animal">🐾 Animal</option>
                <option value="fantasia">✨ Fantasía</option>

            </select>

            <label>Color</label>

            <select name="color">

                <option value="verde">Verde</option>
                <option value="morado">Morado</option>
                <option value="azul">Azul</option>

            </select>

            <button type="submit">Crear Mascota</button>

        </form>

    </div>

</body>

</html>