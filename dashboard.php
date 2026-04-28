<?php
require_once "includes/proteger.php";
require_once "app/controllers/DashboardController.php";

$controller = new DashboardController();
$controller->index();
exit;
?>
