<?php

require_once __DIR__ . "/../../config/database.php";
require_once __DIR__ . "/../models/Usuario.php";

class AuthController
{
    public function login()
    {
        global $bd;

        session_start();

        // --- 1. REDIRECCIÓN SI YA HAY SESIÓN ---
        if (isset($_SESSION["usuario_id"])) {
            header("Location: dashboard.php");
            exit();
        }

        // Variable para mostrar errores en la vista
        $error = "";

        // --- 2. PROCESAR FORMULARIO ---
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            // Validamos que lleguen los datos del formulario
            if (!isset($_POST["email"]) || !isset($_POST["password"])) {
                $error = "Faltan datos del formulario";
                require_once __DIR__ . "/../views/auth/login.php";
                return;
            }

            $email = trim($_POST["email"]);
            $password = $_POST["password"];

            try {
                // Buscar usuario por email usando el modelo
                $usuario = Usuario::obtenerPorEmail($bd, $email);

                // Verificar usuario y contraseña
                if ($usuario && password_verify($password, $usuario["password"])) {

                    // Crear sesión
                    $_SESSION["usuario_id"] = $usuario["id"];
                    $_SESSION["usuario_nombre"] = $usuario["nombre"];

                    // Redirigir al dashboard
                    header("Location: dashboard.php");
                    exit();
                } else {
                    $error = "Credenciales incorrectas";
                }
            } catch (PDOException $e) {
                $error = "Error al iniciar sesión";
            }
        }

        // --- 3. CARGAR VISTA ---
        require_once __DIR__ . "/../views/auth/login.php";
    }
}
