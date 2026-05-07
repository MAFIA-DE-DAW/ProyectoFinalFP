<?php
// ============================================================
// Router principal para Vercel (Serverless PHP)
// ============================================================

$uri  = $_SERVER['REQUEST_URI'];
$path = parse_url($uri, PHP_URL_PATH);

// Calcular root de forma robusta (sin realpath que puede fallar)
$root = __DIR__ . '/../';

// --- DEBUG: ruta temporal para diagnóstico ---
if ($path === '/debug-fs') {
    header('Content-Type: application/json');
    $test = $root . 'assets/img/fondo_normal.jpg';
    echo json_encode([
        '__DIR__'    => __DIR__,
        'root'       => $root,
        'test_file'  => $test,
        'is_file'    => is_file($test),
        'file_exists'=> file_exists($test),
        'realpath'   => realpath($root),
        'ls_assets'  => @scandir($root . 'assets/img/') ?: 'FAILED',
    ], JSON_PRETTY_PRINT);
    exit;
}

// --- 1. Servir archivos estáticos ---
if ($path !== '/' && !str_ends_with($path, '.php')) {
    $clean   = ltrim($path, '/');
    $static1 = $root . $clean;                           // api/../assets/img/xxx
    $static2 = realpath($root) . '/' . $clean;           // path absoluto resuelto

    $static_file = is_file($static1) ? $static1 : (is_file($static2) ? $static2 : null);

    if ($static_file) {
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
    echo '<p>Path: ' . htmlspecialchars($path) . '</p>';
}
