<?php
session_start();

// Si no hay sesión iniciada
if (!isset($_SESSION["usuario_id"])) {

    // Redirigir al login
    header("Location: login.php");
    exit();

}
?>