<?php
// ============================================================
// Configuración de base de datos
// - LOCAL (XAMPP):    conecta a MySQL en 127.0.0.1
// - PRODUCCIÓN:       usa variables de entorno DB_* (Vercel)
// - SUPABASE directo: fallback con credenciales de Supabase
// ============================================================

$db_host     = getenv('DB_HOST')     ?: 'db.hndzzatpgvxsvqittrgf.supabase.co';
$db_name     = getenv('DB_NAME')     ?: 'postgres';
$db_user     = getenv('DB_USER')     ?: 'postgres';
$db_password = getenv('DB_PASSWORD') ?: 'WJ#CR7ZwApedkma';
$db_port     = getenv('DB_PORT')     ?: '5432';

// Detectar si estamos en local (XAMPP) mirando si MySQL está disponible
$es_local = (php_uname('n') !== 'vercel' && file_exists('C:/xampp/mysql/bin/mysqld.exe'));

if ($es_local) {
    // --- LOCAL: XAMPP (MySQL) ---
    try {
        $bd = new PDO(
            'mysql:dbname=ecogotchi;host=127.0.0.1;charset=utf8',
            'root',
            ''
        );
        $bd->exec("SET NAMES utf8mb4");
        $bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $bd->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // Si falla MySQL local, conectar a Supabase
        $es_local = false;
    }
}

if (!$es_local) {
    // --- PRODUCCIÓN / SUPABASE (PostgreSQL) ---
    $cadena_conexion = "pgsql:host={$db_host};port={$db_port};dbname={$db_name};sslmode=require";
    try {
        $bd = new PDO($cadena_conexion, $db_user, $db_password);
        $bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $bd->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die('Error con la base de datos (Supabase): ' . $e->getMessage());
    }
}