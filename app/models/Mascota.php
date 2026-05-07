<?php

// Modelo Mascota
// Se encarga de todas las consultas relacionadas con la tabla "mascotas"
class Mascota
{
    // Método estático para obtener la mascota del usuario
    // Recibe la conexión a la BD ($bd) y el id del usuario
    public static function obtenerPorUsuario($bd, $id_usuario)
    {
        // Consulta SQL para buscar la mascota que pertenece al usuario
        $sql = "SELECT * FROM mascotas WHERE id_usuario = :id_usuario";
        // Preparamos la consulta para evitar SQL injection
        $consulta_mascota = $bd->prepare($sql);
        // Ejecutamos la consulta pasando el id del usuario
        // :id_usuario es el parámetro de la consulta
        $consulta_mascota->execute([':id_usuario' => $id_usuario]);
        // Devolvemos el resultado como array asociativo
        return $consulta_mascota->fetch(PDO::FETCH_ASSOC);
    }

    public static function crear($bd, $id_usuario, $nombre, $tipo, $color)
    {
        $sql = "INSERT INTO mascotas 
            (id_usuario, nombre, tipo, color, hambre, sueno, diversion, higiene, salud, fecha_ultima_actualizacion) 
            VALUES (:id_usuario, :nombre, :tipo, :color, 40, 40, 40, 40, 100, NOW())";

        $consulta_crear_mascota = $bd->prepare($sql);

        return $consulta_crear_mascota->execute([
            ':id_usuario' => $id_usuario,
            ':nombre' => $nombre,
            ':tipo' => $tipo,
            ':color' => $color
        ]);
    }

    // Método para actualizar las estadísticas de la mascota
    public static function actualizarStats($bd, $id_usuario, $hambre, $sueno, $diversion, $higiene, $basura)
    {
        // Consulta SQL para guardar las estadísticas actualizadas
        $sql_update = "UPDATE mascotas 
                       SET hambre = :h, 
                           sueno = :s, 
                           diversion = :d, 
                           higiene = :hi,
                           basura = :b,
                           fecha_ultima_actualizacion = NOW() 
                       WHERE id_usuario = :id";

        // Preparamos la consulta
        $consulta = $bd->prepare($sql_update);

        // Ejecutamos la consulta con los nuevos valores
        return $consulta->execute([
            ':h' => $hambre,
            ':s' => $sueno,
            ':d' => $diversion,
            ':hi' => $higiene,
            ':b' => $basura,
            ':id' => $id_usuario
        ]);
    }

    public function actualizarBasura($bd, $id_usuario, $basura)
    {
        // Consulta SQL para guardar la basura actualizada
        $sql_update = "UPDATE mascotas 
                       SET basura = :b,
                           fecha_ultima_actualizacion = NOW() 
                       WHERE id_usuario = :id";

        // Preparamos la consulta
        $consulta = $bd->prepare($sql_update);

        // Ejecutamos la consulta con los nuevos valores
        return $consulta->execute([
            ':b' => $basura,
            ':id' => $id_usuario
        ]);
    }

    // Método para eliminar la mascota del usuario
    public static function eliminarPorUsuario($bd, $id_usuario)
    {
        // Consulta SQL para borrar la mascota del usuario
        $sql_delete = "DELETE FROM mascotas WHERE id_usuario = :id";

        // Preparamos la consulta
        $consulta = $bd->prepare($sql_delete);

        // Ejecutamos la consulta con el id del usuario
        return $consulta->execute([':id' => $id_usuario]);
    }
    // Método para comprobar si la mascota ha muerto
    public static function estaMuerta($hambre, $sueno, $diversion, $higiene)
    {
        // Si alguna necesidad es menor o igual a 0, la mascota muere
        return ($hambre <= 0 && $sueno <= 0 && $diversion <= 0 && $higiene <= 0);
    }

    // Método para calcular cuántos minutos han pasado desde la última actualización
    public static function calcularMinutosTranscurridos($fecha_ultima_actualizacion)
    {
        // Convertimos la fecha guardada en la BD a timestamp
        $ultima = strtotime($fecha_ultima_actualizacion);

        // Obtenemos el tiempo actual
        $ahora = time();

        // Calculamos la diferencia en segundos
        $diferencia_segundos = $ahora - $ultima;

        // Convertimos la diferencia a minutos enteros
        return floor($diferencia_segundos / 60);
    }
    // Calcula las nuevas estadísticas de la mascota según los minutos transcurridos
    public static function calcularDegradacion($mascota, $minutos)
    {
        $hambre = max(0, $mascota["hambre"] - ($minutos * 2));
        $sueno = max(0, $mascota["sueno"] - ($minutos * 1));
        $diversion = max(0, $mascota["diversion"] - ($minutos * 2));
        $higiene = max(0, $mascota["higiene"] - ($minutos * 1));

        return [
            "hambre" => $hambre,
            "sueno" => $sueno,
            "diversion" => $diversion,
            "higiene" => $higiene
        ];
    }

    // Método para calcular el mensaje de la mascota según su estado
    public static function calcularMensaje($mascota)
    {
        if ($mascota["hambre"] < 30) {
            $mensajes = [
                "¡Tengo mucha hambre! 🍎",
                "Mi barriga ruge... 🍔",
                "¿Me das algo de comer?"
            ];
        } elseif ($mascota["sueno"] < 30) {
            $mensajes = [
                "Tengo mucho sueño... 😴",
                "Necesito dormir un poco",
                "Estoy muy cansado..."
            ];
        } elseif ($mascota["diversion"] < 30) {
            $mensajes = [
                "¡Quiero jugar! 🎾",
                "Estoy aburrido...",
                "¿Jugamos un rato?"
            ];
        } elseif ($mascota["higiene"] < 30) {
            $mensajes = [
                "Necesito un baño 🛁",
                "Estoy muy sucio...",
                "Hora de limpiarme"
            ];
        } elseif ($mascota["basura"]) {
            $mensajes = [
                "Si lo ves caer, hazlo desaparecer (en la papelera).",
                "Haz que tu huella sea limpia, no visible.",
                "Ensuciar es fácil, limpiar es responsabilidad."
            ];
        } else {
            $mensajes = [
                "¡Estoy genial! 🌱",
                "Me siento muy bien hoy",
                "Gracias por cuidarme"
            ];
        }

        // Devolvemos un mensaje aleatorio del grupo correspondiente
        return $mensajes[array_rand($mensajes)];
    }
    // Método para obtener el nombre del archivo de imagen de la mascota
    public static function obtenerImagen($mascota)
    {
        // Si existe mascota y tiene tipo y color, construimos el nombre de la imagen
        if ($mascota && isset($mascota["tipo"], $mascota["color"])) {
            return $mascota["tipo"] . "_" . $mascota["color"] . ".png";
        }

        // Si no hay mascota devolvemos null
        return null;
    }

    // Método para guardar una mascota en el historial antes de eliminarla
    public static function guardarEnHistorial($bd, $mascota, $motivo = "muerte")
    {
        $sql = "INSERT INTO mascotas_historial 
            (id_usuario, nombre, tipo, color, hambre, sueno, diversion, higiene, salud, basura, fecha_ultima_actualizacion, fecha_fin, motivo_fin)
            VALUES 
            (:id_usuario, :nombre, :tipo, :color, :hambre, :sueno, :diversion, :higiene, :salud, :basura, :fecha_ultima_actualizacion, NOW(), :motivo)";

        $consulta = $bd->prepare($sql);

        return $consulta->execute([
            ':id_usuario' => $mascota['id_usuario'],
            ':nombre' => $mascota['nombre'],
            ':tipo' => $mascota['tipo'],
            ':color' => $mascota['color'],
            ':hambre' => $mascota['hambre'],
            ':sueno' => $mascota['sueno'],
            ':diversion' => $mascota['diversion'],
            ':higiene' => $mascota['higiene'],
            ':salud' => $mascota['salud'],
            ':basura' => $mascota['basura'],
            ':fecha_ultima_actualizacion' => $mascota['fecha_ultima_actualizacion'],
            ':motivo' => $motivo
        ]);
    }

    // Método para obtener el historial de mascotas de un usuario
    public static function obtenerHistorialPorUsuario($bd, $id_usuario)
    {
        $sql = "SELECT * FROM mascotas_historial 
            WHERE id_usuario = :id_usuario
            ORDER BY fecha_fin DESC";

        $consulta = $bd->prepare($sql);
        $consulta->execute([
            ':id_usuario' => $id_usuario
        ]);

        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }
}
