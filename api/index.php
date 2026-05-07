<?php
// ============================================================
// Router principal para Vercel (Serverless PHP)
// Todas las peticiones pasan por aquí y se enrutan
// al archivo PHP correspondiente del proyecto
// ============================================================

$uri  = $_SERVER['REQUEST_URI'];
$path = parse_url($uri, PHP_URL_PATH);
$path = trim($path, '/');

// Eliminar extensión .php si viene en la URL
$path = preg_replace('/\.php$/', '', $path);

// Mapa de rutas → archivos PHP en la raíz del proyecto
$routes = [
    ''                  => 'index.php',
    'index'             => 'index.php',
    'login'             => 'login.php',
    'logout'            => 'logout.php',
    'registro'          => 'registro.php',
    'dashboard'         => 'dashboard.php',
    'tienda'            => 'tienda.php',
    'mi-cuenta'         => 'mi_cuenta.php',
    'mi_cuenta'         => 'mi_cuenta.php',
    'baja_usuario'      => 'baja_usuario.php',
    'crear_mascota'     => 'crear_mascota.php',
    'accion_mascota'    => 'accion_mascota.php',
    'completar_mision'  => 'completar_mision.php',
    'comprar_accion'    => 'comprar_accion.php',
    'limpiar_basura'    => 'limpiar_basura.php',
];

// Directorio raíz del proyecto (un nivel arriba de /api)
$root = __DIR__ . '/../';

if (isset($routes[$path])) {
    $file = $root . $routes[$path];
} else {
    // Intentar como archivo .php directo
    $file = $root . $path . '.php';
}

if (file_exists($file)) {
    // Cambiar el directorio de trabajo al raíz para que los
    // includes relativos dentro de cada archivo funcionen
    chdir($root);
    include $file;
} else {
    http_response_code(404);
    echo '<h1>404 - Página no encontrada</h1>';
    echo '<p>Ruta: ' . htmlspecialchars($path) . '</p>';
}
