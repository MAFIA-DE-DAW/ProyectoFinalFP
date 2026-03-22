<?php

// Modelo Usuario
// Se encarga de las consultas relacionadas con los usuarios
class Usuario
{
    // Método para buscar un usuario por su email
    public static function obtenerPorEmail($bd, $email)
    {
        // Consulta SQL para buscar el usuario
        $sql = "SELECT * FROM usuarios WHERE email = :email";

        // Preparamos la consulta para evitar SQL injection
        $consulta = $bd->prepare($sql);

        // Ejecutamos la consulta pasando el email
        $consulta->execute([
            ':email' => $email
        ]);

        // Devolvemos el resultado como array asociativo
        return $consulta->fetch(PDO::FETCH_ASSOC);
    }

    // Método para crear un nuevo usuario
    public static function crear($bd, $nombre, $email, $password_hash)
    {
        // Consulta SQL para insertar el usuario en la base de datos
        $sql = "INSERT INTO usuarios (nombre, email, password) 
                VALUES (:nombre, :email, :password)";

        // Preparamos la consulta
        $consulta = $bd->prepare($sql);

        // Ejecutamos la consulta con los datos del usuario
        return $consulta->execute([
            ':nombre' => $nombre,
            ':email' => $email,
            ':password' => $password_hash
        ]);
    }
}