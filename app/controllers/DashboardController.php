<?php

require_once __DIR__ . "/../../config/database.php";
require_once __DIR__ . "/../models/Mascota.php";
require_once __DIR__ . "/../models/Entorno.php";
require_once __DIR__ . "/../models/Mision.php";
require_once __DIR__ . "/../models/Clima.php";

class DashboardController
{
    public function index()
    {
        global $bd;

        // --- 1. USUARIO ACTUAL ---
        $id_usuario = $_SESSION["usuario_id"];

        // --- 2. CARGA INICIAL DE DATOS ---
        // Obtener mascota
        $mascota = Mascota::obtenerPorUsuario($bd, $id_usuario);

        // Obtener entorno ecológico o crearlo si no existe
        $entorno = Entorno::obtenerOCrear($bd, $id_usuario);

        // --- 3. DEGRADACIÓN AUTOMÁTICA Y MUERTE DE LA MASCOTA ---
        if ($mascota) {

            // Calculamos minutos desde la última actualización
            $minutos = Mascota::calcularMinutosTranscurridos($mascota["fecha_ultima_actualizacion"]);

            // Si ha pasado tiempo, aplicamos degradación
            if ($minutos > 0) {

                // Calculamos las nuevas estadísticas
                $stats = Mascota::calcularDegradacion($mascota, $minutos);

                $hambre = $stats["hambre"];
                $sueno = $stats["sueno"];
                $diversion = $stats["diversion"];
                $higiene = $stats["higiene"];

                if (Mascota::estaMuerta($hambre, $sueno, $diversion, $higiene)) {

                    // Guardamos el nombre de la mascota en sesión
                    $_SESSION["mascota_muerta"] = $mascota["nombre"];

                    // Eliminamos la mascota de la base de datos
                    Mascota::eliminarPorUsuario($bd, $id_usuario);

                    // Reiniciamos el entorno ecológico
                    Entorno::reiniciarEco($bd, $id_usuario);

                    // Indicamos que ya no hay mascota viva
                    $mascota = false;

                    // Recargamos el entorno actualizado
                    $entorno = Entorno::obtenerPorUsuario($bd, $id_usuario);

                } else {

                    // Guardamos las estadísticas actualizadas en la base de datos
                    Mascota::actualizarStats($bd, $id_usuario, $hambre, $sueno, $diversion, $higiene, $mascota['basura']);

                    // Actualizamos también el array local de la mascota
                    $mascota["hambre"] = $hambre;
                    $mascota["sueno"] = $sueno;
                    $mascota["diversion"] = $diversion;
                    $mascota["higiene"] = $higiene;
                }
            }
        }

        // --- 4. CONTROL DE MISIONES Y ECO DEL DÍA ---
        // Contar misiones completadas hoy
        $misiones_hoy = Mision::contarCompletadasHoy($bd, $id_usuario);

        // Si no ha completado ninguna misión hoy, pierde 5 puntos ecológicos
        if ($misiones_hoy == 0) {
            Entorno::bajarEco($bd, $id_usuario, 5);

            // Volvemos a cargar el entorno actualizado
            $entorno = Entorno::obtenerPorUsuario($bd, $id_usuario);
        }

        // Obtener misiones pendientes del día
        $misiones = Mision::obtenerPendientesDelDia($bd, $id_usuario);

        // --- 5. PREPARACIÓN DE VARIABLES VISUALES ---
        // Calculamos el estado visual del entorno según el nivel ecológico
        $visual_entorno = Entorno::calcularEstadoVisual($entorno["nivel_ecologico"]);

        $estado_entorno = $visual_entorno["estado_entorno"];
        $img_fondo = $visual_entorno["img_fondo"];

        // Calculamos el color de la barra ecológica
        $color_barra = Entorno::calcularColorBarra($entorno["nivel_ecologico"]);

        // Guardamos el nivel ecológico para mostrarlo en la vista
        $nivel_eco = $entorno["nivel_ecologico"];

        // Calculamos el mensaje que mostrará la mascota
        $mensaje = "";

        if ($mascota) {
            $mensaje = Mascota::calcularMensaje($mascota);
        }

        // Obtenemos la imagen de la mascota
        $img_mascota = Mascota::obtenerImagen($mascota);

        // Obtenemos la clase visual del clima desde la API
        $clase_clima = Clima::obtenerClaseClima();

        // --- 5 bis. MONEDAS VERDES ---
        require_once __DIR__ . "/../models/Usuario.php";
        $datos_usuario = Usuario::obtenerPorId($bd, $id_usuario);
        $monedas_verdes = $datos_usuario["monedas_verdes"] ?? 0;

        // --- 6. CARGA DE LA VISTA ---
        require_once __DIR__ . "/../views/mascota/dashboard.php";
    }
}