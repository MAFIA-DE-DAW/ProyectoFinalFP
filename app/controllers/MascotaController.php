<?php

require_once __DIR__ . "/../../config/database.php";
require_once __DIR__ . "/../models/Mascota.php";

class MascotaController
{
    public function crear()
    {
        global $bd;

        $error = "";

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $nombre = $_POST["nombre"];
            $tipo = $_POST["tipo"];
            $color = $_POST["color"];

            try {
                Mascota::crear($bd, $_SESSION["usuario_id"], $nombre, $tipo, $color);

                header("Location: dashboard.php");
                exit();
            } catch (PDOException $e) {
                $error = "Error al crear la mascota";
            }
        }

        require_once __DIR__ . "/../views/mascota/crear_mascota.php";
    }
    public function accion()
    {
        global $bd;

        // Comprobamos que el formulario se haya enviado con método POST
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            // Guardamos la acción que ha enviado el usuario
            $accion = $_POST["accion"];

            try {

                // Obtenemos la mascota del usuario desde el modelo
                $mascota = Mascota::obtenerPorUsuario($bd, $_SESSION["usuario_id"]);

                // Comprobamos que el usuario tenga mascota
                if ($mascota) {

                    // Guardamos los valores actuales de las estadísticas
                    $hambre = $mascota["hambre"];
                    $sueno = $mascota["sueno"];
                    $diversion = $mascota["diversion"];
                    $higiene = $mascota["higiene"];

                    // Según la acción se modifican las estadísticas
                    switch ($accion) {
                        case "alimentar":
                            $hambre += 20;
                            break;

                        case "dormir":
                            $sueno += 30;
                            break;

                        case "jugar":
                            $diversion += 20;
                            break;

                        case "limpiar":
                            $higiene += 20;
                            break;

                        default:
                            header("Location: dashboard.php");
                            exit();
                    }

                    // Ningún valor puede superar 100
                    $hambre = min($hambre, 100);
                    $sueno = min($sueno, 100);
                    $diversion = min($diversion, 100);
                    $higiene = min($higiene, 100);

                    // Actualizamos las estadísticas desde el modelo
                    Mascota::actualizarStats($bd, $_SESSION["usuario_id"], $hambre, $sueno, $diversion, $higiene);
                }

                // Redirigimos al dashboard
                header("Location: dashboard.php");
                exit();
            } catch (PDOException $e) {
                echo "Error en la acción";
            }
        }
    }

}
