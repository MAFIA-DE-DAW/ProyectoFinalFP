<?php
// ============================================================
// Router principal para Vercel (Serverless PHP)
// Vercel sirve los archivos estáticos directamente (handle:filesystem)
// Este router solo procesa peticiones PHP
// ============================================================

$uri  = $_SERVER['REQUEST_URI'];
$path = parse_url($uri, PHP_URL_PATH);
$root = __DIR__ . '/../';

// Eliminar extensión .php si viene en la URL
$path = trim($path, '/');
$path = preg_replace('/\.php$/', '', $path);

// Mapa de rutas → archivos PHP del proyecto
$routes = [
    ''                 => 'index.php',
    'index'            => 'index.php',
    'login'            => 'login.php',
    'logout'           => 'logout.php',
    'registro'         => 'registro.php',
    'dashboard'        => 'dashboard.php',
    'tienda'           => 'tienda.php',
    'mi-cuenta'        => 'mi_cuenta.php',
    'mi_cuenta'        => 'mi_cuenta.php',
    'baja_usuario'     => 'baja_usuario.php',
    'crear_mascota'    => 'crear_mascota.php',
    'accion_mascota'   => 'accion_mascota.php',
    'completar_mision' => 'completar_mision.php',
    'comprar_accion'   => 'comprar_accion.php',
    'limpiar_basura'   => 'limpiar_basura.php',
];

if (isset($routes[$path])) {
    $file = $root . $routes[$path];
} else {
    $file = $root . $path . '.php';
}

header('Content-Type: text/html; charset=utf-8');

if (file_exists($file)) {
    chdir($root);
    include $file;
} else {
    http_response_code(404);
    echo '<h1>404 - Página no encontrada</h1>';
    echo '<p>Ruta: ' . htmlspecialchars($path) . '</p>';
}
