<!DOCTYPE html>
<html>

<head>
    <title>Login</title>
    <link rel="stylesheet" href="/ProyectoFinalFP/assets/css/styles.css">
</head>

<body class="login">

    <div class="form-container">

        <h2>Iniciar sesión</h2>
        <?php if (!empty($error)): ?>
            <p class="error-login"><?php echo $error; ?></p>
        <?php endif; ?>

        <form method="POST">
            <label>Email</label>
            <input type="email" name="email" required>

            <label>Contraseña</label>
            <input type="password" name="password" required>

            <button type="submit">Entrar</button>
        </form>

        <button type="button" class="btn-secundario" onclick="window.location.href='registro.php'">
            Crear cuenta nueva
        </button>

    </div>

</body>

</html>