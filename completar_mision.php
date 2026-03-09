<?php

session_start();
require_once "includes/conexion.php";

$id_usuario = $_SESSION["usuario_id"];
$mision_id = $_POST["mision_id"];

/* obtener recompensa */

$sql = "SELECT recompensa FROM misiones WHERE id = :id";

$consulta = $bd->prepare($sql);
$consulta->execute([
    ":id" => $mision_id
]);

$mision = $consulta->fetch(PDO::FETCH_ASSOC);

$puntos = $mision["recompensa"];


/* guardar misión completada */

$sql_guardar = "INSERT INTO misiones_completadas
(id_usuario,id_mision,fecha)
VALUES (:usuario,:mision,NOW())";

$bd->prepare($sql_guardar)->execute([
    ":usuario" => $id_usuario,
    ":mision" => $mision_id
]);


/* subir eco */

$sql_update = "UPDATE entorno
SET nivel_ecologico = LEAST(100, nivel_ecologico + :puntos)
WHERE id_usuario = :usuario";

$bd->prepare($sql_update)->execute([
    ":puntos" => $puntos,
    ":usuario" => $id_usuario
]);


header("Location: dashboard.php");
exit;
