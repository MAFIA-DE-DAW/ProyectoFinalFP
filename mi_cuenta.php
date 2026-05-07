<?php
session_start();

if (!isset($_SESSION["usuario_id"])) {
    header("Location: login.php");
    exit();
}

require_once __DIR__ . "/app/controllers/HistorialController.php";

$controller = new HistorialController();
$controller->index();