<?php
// ============================================================
// Router principal para Vercel (Serverless PHP)
// Sirve archivos estáticos Y páginas PHP
// ============================================================

$uri  = $_SERVER['REQUEST_URI'];
$path = parse_url($uri, PHP_URL_PATH);
$root = realpath(__DIR__ . '/../') . '/';

// --- 1. Servir archivos estáticos (CSS, JS, imágenes…) ---
if ($path !== '/' && !str_ends_with($path, '.php')) {
    $static_file = $root . ltrim($path, '/');
    if (is_file($static_file)) {
        $ext = strtolower(pathinfo($static_file, PATHINFO_EXTENSION));
        $types = [
            'css'   => 'text/css; charset=utf-8',
            'js'    => 'application/javascript; charset=utf-8',
            'png'   => 'image/png',
            'jpg'   => 'image/jpeg',
            'jpeg'  => 'image/jpeg',
            'gif'   => 'image/gif',
            'svg'   => 'image/svg+xml',
            'ico'   => 'image/x-icon',
            'webp'  => 'image/webp',
            'woff'  => 'font/woff',
            'woff2' => 'font/woff2',
            'ttf'   => 'font/ttf',
        ];
        header('Content-Type: ' . ($types[$ext] ?? 'application/octet-stream'));
        header('Cache-Control: public, max-age=604800');
        header('Content-Length: ' . filesize($static_file));
        readfile($static_file);
        exit;
    }
}

// --- 2. Enrutar peticiones PHP ---
$path = trim($path, '/');
$path = preg_replace('/\.php$/', '', $path);

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

$file = isset($routes[$path]) ? $root . $routes[$path] : $root . $path . '.php';

header('Content-Type: text/html; charset=utf-8');

if (file_exists($file)) {
    chdir($root);
    include $file;
} else {
    http_response_code(404);
    echo '<h1>404 - Página no encontrada</h1>';
}
