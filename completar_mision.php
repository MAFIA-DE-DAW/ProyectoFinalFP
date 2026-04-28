<?php
require_once "includes/proteger.php";
require_once "app/controllers/MisionController.php";

$controller = new MisionController();
$controller->completar();
exit;
?>