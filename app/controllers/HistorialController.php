<?php

require_once __DIR__ . "/../../config/database.php";
require_once __DIR__ . "/../models/Mascota.php";

class HistorialController
{
    public function index()
    {
        global $bd;

        $id_usuario = $_SESSION["usuario_id"];

        $historial = Mascota::obtenerHistorialPorUsuario($bd, $id_usuario);

        require_once __DIR__ . "/../views/mascota/historial.php";
    }
}