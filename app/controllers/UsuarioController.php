<?php

require_once __DIR__ . "/../../config/database.php";
require_once __DIR__ . "/../models/Usuario.php";

class UsuarioController
{
    // Método para mostrar y procesar el registro de usuario
    public function registro()
    {
        global $bd;

        // Variable para guardar mensajes de error
        $error = "";

        // Comprobamos si el formulario se ha enviado con método POST
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            // Recogemos los datos enviados desde el formulario
            $nombre = trim($_POST["nombre"]);
            $email = trim($_POST["email"]);
            $password = $_POST["password"];

            // Encriptamos la contraseña antes de guardarla
            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            try {
                // Comprobamos si ya existe un usuario con ese email
                $usuario_existente = Usuario::obtenerPorEmail($bd, $email);

                if ($usuario_existente) {
                    $error = "Este email ya está registrado";
                } else {
                    // Creamos el nuevo usuario
                    Usuario::crear($bd, $nombre, $email, $password_hash);

                    // Redirigimos al login
                    header("Location: login.php");
                    exit();
                }
            } catch (PDOException $e) {
                $error = "Error al registrar usuario";
            }
        }

        // Cargamos la vista del formulario de registro
        require_once __DIR__ . "/../views/auth/registro.php";
    }
}
