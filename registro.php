<?php
require_once "includes/conexion.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nombre = $_POST["nombre"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Encriptar contraseña
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    try {
        // Comprobar si el email ya existe
        $sql_comprobar = "SELECT id FROM usuarios WHERE email = :email";
        $consulta_email = $bd->prepare($sql_comprobar);
        $consulta_email->execute([
            ':email' => $email
        ]);

        // Si encuentra algún resultado
        if ($consulta_email->rowCount() > 0) {
            echo "Este email ya está registrado";
        } else {
            // Insertar usuario
            $sql_insertar = "INSERT INTO usuarios (nombre, email, password) 
                             VALUES (:nombre, :email, :password)";
            $consulta_insertar = $bd->prepare($sql_insertar);

            $consulta_insertar->execute([
                ':nombre' => $nombre,
                ':email' => $email,
                ':password' => $password_hash
            ]);

            // REDIRECCIÓN AUTOMÁTICA AL LOGIN
            header("Location: login.php");
            exit();
        }
    } catch (PDOException $e) {
        echo "Error al registrar usuario";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Registro - EcoGotchi</title>
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>

    <div class="form-container">

        <h2>Crear cuenta</h2>

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