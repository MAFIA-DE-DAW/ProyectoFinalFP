<?php

// Modelo Mision
// Se encarga de las consultas relacionadas con la tabla "misiones"
class Mision
{
    // Método para obtener 3 misiones no completadas hoy por el usuario
    public static function obtenerPendientesDelDia($bd, $id_usuario)
    {
        // Consulta SQL para traer misiones que el usuario no ha completado hoy
        $sql_misiones = "
        SELECT * FROM misiones
        WHERE id NOT IN (
            SELECT id_mision FROM misiones_completadas
            WHERE id_usuario = :usuario AND DATE(fecha) = CURDATE()
        )
        ORDER BY RAND() LIMIT 3";

        // Preparamos la consulta
        $consulta = $bd->prepare($sql_misiones);

        // Ejecutamos la consulta pasando el id del usuario
        $consulta->execute([':usuario' => $id_usuario]);

        // Devolvemos las misiones en un array
        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    // Método para contar cuántas misiones ha completado hoy el usuario
    public static function contarCompletadasHoy($bd, $id_usuario)
    {
        // Consulta SQL para contar las misiones completadas hoy
        $sql_check = "
        SELECT COUNT(*) 
        FROM misiones_completadas
        WHERE id_usuario = :usuario
        AND DATE(fecha) = CURDATE()
        ";

        // Preparamos la consulta
        $consulta_check = $bd->prepare($sql_check);

        // Ejecutamos la consulta con el id del usuario
        $consulta_check->execute([':usuario' => $id_usuario]);

        // Devolvemos el total
        return $consulta_check->fetchColumn();
    }
    // Método para obtener la recompensa de una misión
    public static function obtenerRecompensa($bd, $id_mision)
    {
        $sql = "SELECT recompensa FROM misiones WHERE id = :id";

        $consulta = $bd->prepare($sql);
        $consulta->execute([
            ":id" => $id_mision
        ]);

        $mision = $consulta->fetch(PDO::FETCH_ASSOC);

        return $mision["recompensa"];
    }
}
