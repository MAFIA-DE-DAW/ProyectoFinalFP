<?php

// Modelo Tienda
// Se encarga de las consultas relacionadas con la tienda y las compras
class Tienda
{
    // Obtener todas las acciones disponibles en la tienda
    public static function obtenerAcciones($bd)
    {
        $sql = "SELECT * FROM tienda_acciones ORDER BY coste ASC";
        $consulta = $bd->query($sql);
        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener una acción específica por su ID
    public static function obtenerAccion($bd, $id_accion)
    {
        $sql = "SELECT * FROM tienda_acciones WHERE id = :id";
        $consulta = $bd->prepare($sql);
        $consulta->execute([':id' => $id_accion]);
        return $consulta->fetch(PDO::FETCH_ASSOC);
    }

    // Registrar una compra en el historial
    public static function registrarCompra($bd, $id_usuario, $id_accion)
    {
        $sql = "INSERT INTO compras (id_usuario, id_accion) VALUES (:id_u, :id_a)";
        $consulta = $bd->prepare($sql);
        return $consulta->execute([
            ':id_u' => $id_usuario,
            ':id_a' => $id_accion
        ]);
    }

    // Obtener el historial de acciones compradas por un usuario
    public static function obtenerHistorial($bd, $id_usuario)
    {
        $sql = "SELECT c.*, t.titulo, t.icono, t.impacto_real 
                FROM compras c
                JOIN tienda_acciones t ON c.id_accion = t.id
                WHERE c.id_usuario = :id_u
                ORDER BY c.fecha DESC";
        $consulta = $bd->prepare($sql);
        $consulta->execute([':id_u' => $id_usuario]);
        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }
}
