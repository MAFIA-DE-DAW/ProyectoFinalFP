<?php

// Modelo Entorno
// Se encarga de las consultas relacionadas con la tabla "entorno"
class Entorno
{
    // Método para obtener el entorno ecológico del usuario
    public static function obtenerPorUsuario($bd, $id_usuario)
    {
        // Consulta SQL para buscar el entorno del usuario
        $sql_entorno = "SELECT * FROM entorno WHERE id_usuario = :id";

        // Preparamos la consulta
        $consulta_entorno = $bd->prepare($sql_entorno);

        // Ejecutamos la consulta pasando el id del usuario
        $consulta_entorno->execute([':id' => $id_usuario]);

        // Devolvemos el entorno como array asociativo
        return $consulta_entorno->fetch(PDO::FETCH_ASSOC);
    }

    // Método para comprobar si el usuario ya tiene entorno creado
    public static function existe($bd, $id_usuario)
    {
        // Reutilizamos la búsqueda del entorno
        $entorno = self::obtenerPorUsuario($bd, $id_usuario);

        // Si existe devuelve true, si no existe devuelve false
        return $entorno ? true : false;
    }

    // Método para crear el entorno inicial del usuario
    public static function crearInicial($bd, $id_usuario)
    {
        // Insertamos un entorno por defecto para el usuario
        $sql_crear = "INSERT INTO entorno (id_usuario, nivel_ecologico, estado_entorno) 
                      VALUES (:id, 50, 'normal')";

        // Preparamos la consulta
        $consulta = $bd->prepare($sql_crear);

        // Ejecutamos la consulta con el id del usuario
        return $consulta->execute([':id' => $id_usuario]);
    }
    // Método para obtener el entorno del usuario
    // Si no existe, lo crea y devuelve un entorno inicial
    public static function obtenerOCrear($bd, $id_usuario)
    {
        // Intentamos obtener el entorno actual
        $entorno = self::obtenerPorUsuario($bd, $id_usuario);

        // Si no existe, lo creamos
        if (!$entorno) {
            self::crearInicial($bd, $id_usuario);

            // Devolvemos un entorno inicial
            $entorno = [
                "nivel_ecologico" => 50,
                "estado_entorno" => "normal"
            ];
        }

        return $entorno;
    }
    // Método para bajar el nivel ecológico del usuario
    public static function bajarEco($bd, $id_usuario, $cantidad)
    {
        // Restamos eco sin permitir valores negativos
        $sql_bajar = "UPDATE entorno 
                      SET nivel_ecologico = GREATEST(0, nivel_ecologico - :cantidad) 
                      WHERE id_usuario = :usuario";

        // Preparamos la consulta
        $consulta = $bd->prepare($sql_bajar);

        // Ejecutamos la consulta con el id del usuario y la cantidad a restar
        return $consulta->execute([
            ':cantidad' => $cantidad,
            ':usuario' => $id_usuario
        ]);
    }

    // Método para reiniciar el entorno a los valores iniciales
    public static function reiniciarEco($bd, $id_usuario)
    {
        // Consulta SQL para resetear el nivel ecológico y el estado del entorno
        $sql_reset_eco = "UPDATE entorno 
                          SET nivel_ecologico = 50,
                              estado_entorno = 'normal',
                              fecha_ultima_actualizacion = NOW()
                          WHERE id_usuario = :usuario";

        // Preparamos la consulta
        $consulta = $bd->prepare($sql_reset_eco);

        // Ejecutamos la consulta con el id del usuario
        return $consulta->execute([':usuario' => $id_usuario]);
    }

    // Método para calcular el estado visual del entorno según el nivel ecológico
    public static function calcularEstadoVisual($nivel_eco)
    {
        // Según el nivel ecológico devolvemos el estado y la imagen de fondo
        switch (true) {
            case ($nivel_eco > 70):
                return [
                    "estado_entorno" => "verde",
                    "img_fondo" => "fondo_bueno.jpg"
                ];

            case ($nivel_eco >= 50):
                return [
                    "estado_entorno" => "normal",
                    "img_fondo" => "fondo_normal.jpg"
                ];

            case ($nivel_eco >= 21):
                return [
                    "estado_entorno" => "malo",
                    "img_fondo" => "fondo_malo.jpg"
                ];

            default:
                return [
                    "estado_entorno" => "extremo",
                    "img_fondo" => "fondo_chungo.jpg"
                ];
        }
    }

    // Método para calcular el color de la barra ecológica
    public static function calcularColorBarra($nivel_eco)
    {
        if ($nivel_eco > 50) {
            $ratio = ($nivel_eco - 50) / 50;
            $r = 255 * (1 - $ratio);
            $g = 255;
            $b = 0;
        } elseif ($nivel_eco > 20) {
            $ratio = ($nivel_eco - 20) / 30;
            $r = 255;
            $g = 255 * $ratio;
            $b = 0;
        } else {
            $r = 180;
            $g = 0;
            $b = 0;
        }

        return "rgb($r,$g,$b)";
    }
    // Método para subir el nivel ecológico sin superar 100
    public static function subirEco($bd, $id_usuario, $puntos)
    {
        $sql_update = "UPDATE entorno
                       SET nivel_ecologico = LEAST(100, nivel_ecologico + :puntos)
                       WHERE id_usuario = :usuario";

        $consulta = $bd->prepare($sql_update);

        return $consulta->execute([
            ":puntos" => $puntos,
            ":usuario" => $id_usuario
        ]);
    }
}
