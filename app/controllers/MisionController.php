<?php

require_once __DIR__ . "/../../config/database.php";
require_once __DIR__ . "/../models/Mision.php";
require_once __DIR__ . "/../models/MisionCompletada.php";
require_once __DIR__ . "/../models/Entorno.php";
require_once __DIR__ . "/../models/Usuario.php";

class MisionController
{
    public function completar()
    {
        global $bd;

        // Validamos que la petición llegue por POST
        if ($_SERVER["REQUEST_METHOD"] != "POST") {
            header("Location: dashboard.php");
            exit;
        }

        // Validamos que exista el dato de la misión
        if (!isset($_POST["mision_id"])) {
            header("Location: dashboard.php");
            exit;
        }

        $id_usuario = $_SESSION["usuario_id"];
        $mision_id = $_POST["mision_id"];

        try {

            // Obtener recompensa
            $recompensas = Mision::obtenerRecompensaCompleta($bd, $mision_id);
            $puntos_eco = $recompensas["recompensa"] ?? null;
            $puntos_monedas = $recompensas["puntos_monedas"] ?? 0;

            // Validamos que la misión exista
            if (!$puntos_eco) {
                header("Location: dashboard.php");
                exit;
            }

            // Evitamos que la misión se complete más de una vez al día
            if (MisionCompletada::yaCompletadaHoy($bd, $id_usuario, $mision_id)) {
                header("Location: dashboard.php");
                exit;
            }

            // Guardar misión completada
            MisionCompletada::guardar($bd, $id_usuario, $mision_id);

            // Subir eco
            Entorno::subirEco($bd, $id_usuario, $puntos_eco);

            // Sumar monedas verdes al usuario
            Usuario::sumarMonedas($bd, $id_usuario, $puntos_monedas);

            // Redirigir al dashboard
            header("Location: dashboard.php");
            exit;
        } catch (PDOException $e) {
            echo "Error al completar misión";
        }
    }
}
