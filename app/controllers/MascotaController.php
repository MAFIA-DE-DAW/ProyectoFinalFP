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
                    $basura = $mascota["basura"];

                    // Según la acción se modifican las estadísticas
                    switch ($accion) {
                        case "alimentar":
                            $hambre += 20;
                            $higiene -= 5;
                            $basura += 1;
                            break;

                        case "dormir":
                            $sueno += 30;
                            $hambre -= 5;
                            break;

                        case "jugar":
                            $diversion += 20;
                            $higiene -= 5; 
                            $sueno -= 5; 
                            break;

                        case "duchar":
                            $higiene += 20;
                            $diversion -= 5;
                            break;

                        default:
                            header("Location: dashboard.php");
                            exit();
                    }

                    // Ningún valor puede superar 100
                    $hambre = max(0,min($hambre, 100));
                    $sueno =  max(0,min($sueno, 100));
                    $diversion = max(0,min($diversion, 100));
                    $higiene = max(0,min($higiene, 100));
                    $basura = min($basura, 20);
                    
                    // Actualizamos las estadísticas desde el modelo
                    Mascota::actualizarStats($bd, $_SESSION["usuario_id"], $hambre, $sueno, $diversion, $higiene, $basura);
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
