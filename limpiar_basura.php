<?php
require_once "config/database.php";
require_once "includes/proteger.php";
require_once "app/models/Mascota.php";
require_once "app/models/Entorno.php";

$mascotaModel = new Mascota();

$mascotaModel->actualizarBasura($bd, $_SESSION["usuario_id"], --$_POST["basura"]);

$entorno = new Entorno();
$entorno->subirEco($bd, $_SESSION["usuario_id"], 3);
header("Location: dashboard.php");
exit;
?>
