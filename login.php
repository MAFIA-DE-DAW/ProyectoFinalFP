<?php
require_once "includes/conexion.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST["email"];
    $password = $_POST["password"];

    try {

        // Buscar usuario por email
        $sql = "SELECT * FROM usuarios WHERE email = :email";
        $consulta_usuario = $bd->prepare($sql);

        $consulta_usuario->execute([
            ':email' => $email
        ]);

        $usuario = $consulta_usuario->fetch(PDO::FETCH_ASSOC);

        // Si el usuario existe
        if ($usuario) {

            // Verificar contraseña
            if (password_verify($password, $usuario["password"])) {

                // Crear sesión
                $_SESSION["usuario_id"] = $usuario["id"];
                $_SESSION["usuario_nombre"] = $usuario["nombre"];

                // Redirigir al dashboard
                header("Location: dashboard.php");
                exit();
            } else {

                echo "Contraseña incorrecta";
            }
        } else {

            echo "Usuario no encontrado";
        }
    } catch (PDOException $e) {

        echo "Error al iniciar sesión";
    }
}
?>

<!DOCTYPE html>
<html>

<head>

    <title>Login</title>

    <link rel="stylesheet" href="css/styles.css">

</head>

<body>

    <div class="form-container">

        <h2>Iniciar sesión</h2>

        <form method="POST">

            <label>Email</label>
            <input type="email" name="email" required>

            <label>Contraseña</label>
            <input type="password" name="password" required>

            <button type="submit">Entrar</button>

        </form>

    </div>

</body>

</html>