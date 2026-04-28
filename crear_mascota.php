<?php
require_once "includes/proteger.php";
require_once "app/controllers/MascotaController.php";

$controlador = new MascotaController();
$controlador->crear();
