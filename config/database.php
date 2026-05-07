<?php
// ============================================================
// Configuración de base de datos
// - LOCAL (XAMPP):    conecta a MySQL en 127.0.0.1
// - PRODUCCIÓN:       usa variables de entorno DB_* (Vercel)
// - SUPABASE pooler:  fallback con pooler IPv4 (evita error IPv6)
// ============================================================

// El pooler de Supabase usa IPv4 y evita el error de Vercel.
// Host pooler: Settings → Database → Connection Pooling → Host
// Puerto sesión: 5432 | Puerto transacción: 6543
$db_host     = getenv('DB_HOST')     ?: 'aws-0-eu-central-1.pooler.supabase.com';
$db_name     = getenv('DB_NAME')     ?: 'postgres';
$db_user     = getenv('DB_USER')     ?: 'postgres.hndzzatpgvxsvqittrgf';
$db_password = getenv('DB_PASSWORD') ?: 'WJ#CR7ZwApedkma';
$db_port     = getenv('DB_PORT')     ?: '6543';

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
    // --- PRODUCCIÓN / SUPABASE pooler (PostgreSQL) ---
    // El pooler usa IPv4 y funciona correctamente en Vercel
    $cadena_conexion = "pgsql:host={$db_host};port={$db_port};dbname={$db_name};sslmode=require";
    try {
        $bd = new PDO($cadena_conexion, $db_user, $db_password);
        $bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $bd->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        // Necesario para el modo transacción del pooler (PgBouncer)
        $bd->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);
    } catch (PDOException $e) {
        die('Error con la base de datos (Supabase): ' . $e->getMessage());
    }
}