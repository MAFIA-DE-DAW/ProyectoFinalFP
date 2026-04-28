<?php

require_once __DIR__ . "/../../config/database.php";
require_once __DIR__ . "/../models/Tienda.php";
require_once __DIR__ . "/../models/Usuario.php";

class TiendaController
{
    public function index()
    {
        global $bd;

        if (!isset($_SESSION["usuario_id"])) {
            header("Location: login.php");
            exit;
        }

        $id_usuario = $_SESSION["usuario_id"];

        // Obtener monedas del usuario
        $usuario = Usuario::obtenerPorId($bd, $id_usuario);
        $monedas = $usuario["monedas_verdes"];

        // Obtener acciones de la tienda
        $acciones = Tienda::obtenerAcciones($bd);

        // Obtener historial de compras
        $historial = Tienda::obtenerHistorial($bd, $id_usuario);

        require_once __DIR__ . "/../views/tienda.php";
    }

    public function comprar()
    {
        global $bd;

        if ($_SERVER["REQUEST_METHOD"] != "POST" || !isset($_POST["accion_id"])) {
            header("Location: tienda.php");
            exit;
        }

        $id_usuario = $_SESSION["usuario_id"];
        $id_accion = $_POST["accion_id"];

        try {
            // Obtener datos de la acción
            $accion = Tienda::obtenerAccion($bd, $id_accion);

            if (!$accion) {
                $_SESSION["error_tienda"] = "La acción no existe.";
                header("Location: tienda.php");
                exit;
            }

            // Obtener monedas del usuario
            $usuario = Usuario::obtenerPorId($bd, $id_usuario);
            
            if ($usuario["monedas_verdes"] < $accion["coste"]) {
                $_SESSION["error_tienda"] = "No tienes suficientes Monedas Verdes 💚.";
                header("Location: tienda.php");
                exit;
            }

            // Procesar compra
            $bd->beginTransaction();

            Usuario::restarMonedas($bd, $id_usuario, $accion["coste"]);
            Tienda::registrarCompra($bd, $id_usuario, $id_accion);

            $bd->commit();

            $_SESSION["mensaje_tienda"] = "¡Gracias por tu contribución! Has realizado: " . $accion["titulo"];
            header("Location: tienda.php");
            exit;

        } catch (Exception $e) {
            if ($bd->inTransaction()) $bd->rollBack();
            $_SESSION["error_tienda"] = "Error al procesar la compra.";
            header("Location: tienda.php");
            exit;
        }
    }
}
