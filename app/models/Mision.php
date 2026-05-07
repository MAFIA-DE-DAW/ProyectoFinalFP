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
            WHERE id_usuario = :usuario AND fecha >= :hoy AND fecha < :manana
        )";

        // Preparamos la consulta
        $consulta = $bd->prepare($sql_misiones);

        // Ejecutamos la consulta pasando el id del usuario
        $consulta->execute([
            ':usuario' => $id_usuario,
            ':hoy'     => date('Y-m-d'),
            ':manana'  => date('Y-m-d', strtotime('+1 day')),
        ]);

        // Devolvemos 3 misiones en orden aleatorio (shuffle en PHP = compatible MySQL y PostgreSQL)
        $misiones = $consulta->fetchAll(PDO::FETCH_ASSOC);
        shuffle($misiones);
        return array_slice($misiones, 0, 3);
    }

    // Método para contar cuántas misiones ha completado hoy el usuario
    public static function contarCompletadasHoy($bd, $id_usuario)
    {
        // Consulta SQL para contar las misiones completadas hoy
        $sql_check = "
        SELECT COUNT(*) 
        FROM misiones_completadas
        WHERE id_usuario = :usuario
        AND fecha >= :hoy AND fecha < :manana
        ";

        // Preparamos la consulta
        $consulta_check = $bd->prepare($sql_check);

        // Ejecutamos la consulta con el id del usuario
        $consulta_check->execute([
            ':usuario' => $id_usuario,
            ':hoy'     => date('Y-m-d'),
            ':manana'  => date('Y-m-d', strtotime('+1 day')),
        ]);

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

    // Método para obtener la recompensa completa de una misión (eco + monedas)
    public static function obtenerRecompensaCompleta($bd, $id_mision)
    {
        $sql = "SELECT recompensa, puntos_monedas FROM misiones WHERE id = :id";

        $consulta = $bd->prepare($sql);
        $consulta->execute([
            ":id" => $id_mision
        ]);

        return $consulta->fetch(PDO::FETCH_ASSOC);
    }
}
