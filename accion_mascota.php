<?php
require_once "includes/proteger.php";
require_once "app/controllers/MascotaController.php";

$controller = new MascotaController();
$controller->accion();
exit();
?>