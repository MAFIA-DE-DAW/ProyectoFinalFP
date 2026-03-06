<?php
// Cadena de conexión a la base de datos MySQL
$cadena_conexion =  'mysql:dbname=ecogotchi;host=127.0.0.1;charset=utf8';
$usuario = 'root';
$clave = '';
try{
    // Crear conexión con PDO
    $bd = new PDO($cadena_conexion, $usuario, $clave);
    // Configurar PDO para que muestre errores como excepciones
    $bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Error con la base de datos: ' . $e->getMessage();
}