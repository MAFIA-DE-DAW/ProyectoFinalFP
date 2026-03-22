<!DOCTYPE html>
<html>

<head>
    <title>Registro - EcoGotchi</title>
    <link rel="stylesheet" href="/ProyectoFinalFP/assets/css/styles.css">
</head>

<body class="login">

    <div class="form-container">

        <h2>Crear cuenta</h2>
        <?php if (!empty($error)) : ?>
            <p><?php echo $error; ?></p>
        <?php endif; ?>

        <form method="POST">
            <label>Nombre</label>
            <input type="text" name="nombre" required>

            <label>Email</label>
            <input type="email" name="email" required>

            <label>Contraseña</label>
            <input type="password" name="password" required>

            <button type="submit">Registrarse</button>
        </form>

        <button type="button" class="btn-secundario" onclick="window.location.href='login.php'">
            Ya tengo una cuenta
        </button>

    </div>

</body>

</html>