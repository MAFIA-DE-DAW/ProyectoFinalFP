<?php
// ============================================================
// Configuración de base de datos
// - LOCAL (XAMPP): conecta a MySQL en 127.0.0.1
// - PRODUCCIÓN (Supabase): lee las variables de entorno DB_*
// ============================================================

// Detectar si estamos en producción (variables de entorno definidas)
$db_host     = getenv('DB_HOST');
$db_name     = getenv('DB_NAME');
$db_user     = getenv('DB_USER');
$db_password = getenv('DB_PASSWORD');
$db_port     = getenv('DB_PORT');

if ($db_host) {
    // --- PRODUCCIÓN: Supabase (PostgreSQL) ---
    $cadena_conexion = "pgsql:host={$db_host};port={$db_port};dbname={$db_name};sslmode=require";
    $usuario = $db_user;
    $clave   = $db_password;

    try {
        $bd = new PDO($cadena_conexion, $usuario, $clave);
        $bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $bd->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die('Error con la base de datos (Supabase): ' . $e->getMessage());
    }
} else {
    // --- LOCAL: XAMPP (MySQL) ---
    $cadena_conexion = 'mysql:dbname=ecogotchi;host=127.0.0.1;charset=utf8';
    $usuario = 'root';
    $clave   = '';

    try {
        $bd = new PDO($cadena_conexion, $usuario, $clave);
        $bd->exec("SET NAMES utf8mb4");
        $bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $bd->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die('Error con la base de datos (local): ' . $e->getMessage());
    }
}