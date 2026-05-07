<?php

// Modelo MisionCompletada
// Se encarga de guardar las misiones completadas por el usuario
class MisionCompletada
{
    // Guarda una misión completada con la fecha actual
    public static function guardar($bd, $id_usuario, $id_mision)
    {
        $sql_guardar = "INSERT INTO misiones_completadas
        (id_usuario, id_mision, fecha)
        VALUES (:usuario, :mision, NOW())";

        $consulta = $bd->prepare($sql_guardar);

        return $consulta->execute([
            ":usuario" => $id_usuario,
            ":mision" => $id_mision
        ]);
    }

        // Comprueba si una misión ya fue completada hoy por el usuario
    public static function yaCompletadaHoy($bd, $id_usuario, $id_mision)
    {
        $sql = "SELECT COUNT(*) FROM misiones_completadas
                WHERE id_usuario = :usuario
                AND id_mision = :mision
                AND fecha >= :hoy AND fecha < :manana";

        $consulta = $bd->prepare($sql);
        $consulta->execute([
            ":usuario" => $id_usuario,
            ":mision"  => $id_mision,
            ":hoy"     => date('Y-m-d'),
            ":manana"  => date('Y-m-d', strtotime('+1 day')),
        ]);

        return $consulta->fetchColumn() > 0;
    }
}