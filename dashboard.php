<?php
// --- 1. SEGURIDAD Y CONEXIÓN ---
require_once "includes/proteger.php"; // Verifica que el usuario haya iniciado sesión
require_once "includes/conexion.php"; // Conecta a la base de datos MySQL

// --- 2. OBTENCIÓN DE DATOS DE LA MASCOTA ---
// Buscamos en la tabla 'mascotas' la fila que coincida con el ID del usuario actual
$sql = "SELECT * FROM mascotas WHERE id_usuario = :id_usuario";
$consulta_mascota = $bd->prepare($sql);
$consulta_mascota->execute([':id_usuario' => $_SESSION["usuario_id"]]);

// Guardamos los datos en la variable $mascota (será un array o 'false' si no tiene)
$mascota = $consulta_mascota->fetch(PDO::FETCH_ASSOC);

// --- 3. LÓGICA DE TIEMPO REAL (DEGRADACIÓN) ---
if ($mascota) {
    // Calculamos cuánto tiempo ha pasado desde la última vez que se actualizó la mascota
    $ultima = strtotime($mascota["fecha_ultima_actualizacion"]);
    $ahora = time();
    $diferencia_segundos = $ahora - $ultima;
    $minutos = floor($diferencia_segundos / 60);

    // Si ha pasado al menos 1 minuto, bajamos las estadísticas automáticamente
    if ($minutos > 0) {
        // max(0, ...) asegura que la estadística nunca baje de cero
        $hambre   = max(0, $mascota["hambre"] - ($minutos * 2));   // Baja 2 puntos por minuto
        $sueno    = max(0, $mascota["sueno"] - ($minutos * 1));    // Baja 1 punto por minuto
        $diversion = max(0, $mascota["diversion"] - ($minutos * 2)); // Baja 2 puntos por minuto
        $higiene   = max(0, $mascota["higiene"] - ($minutos * 1));   // Baja 1 punto por minuto

        // Guardamos los nuevos valores en la Base de Datos y actualizamos la fecha al momento actual (NOW())
        $sql_update = "UPDATE mascotas SET 
                        hambre = :h, sueno = :s, diversion = :d, 
                        higiene = :hi, fecha_ultima_actualizacion = NOW() 
                      WHERE id_usuario = :id";

        $bd->prepare($sql_update)->execute([
            ':h'  => $hambre,
            ':s'  => $sueno,
            ':d'  => $diversion,
            ':hi' => $higiene,
            ':id' => $_SESSION["usuario_id"]
        ]);

        // Actualizamos los valores en la variable local para que el HTML muestre los datos nuevos
        $mascota["hambre"] = $hambre;
        $mascota["sueno"] = $sueno;
        $mascota["diversion"] = $diversion;
        $mascota["higiene"] = $higiene;
    }

    // --- 4. LÓGICA VISUAL (FONDOS Y SPRITES) ---
    // Determinamos qué fondo mostrar según el nivel ecológico
    $nivel_eco = $mascota['nivel_ecologico'] ?? 50; // Si no existe el dato, usamos 50 por defecto

    if ($nivel_eco > 70) {
        $img_fondo = "fondo_bueno.png"; // Mundo verde y limpio
    } elseif ($nivel_eco < 30) {
        $img_fondo = "fondo_malo.png";  // Mundo contaminado
    } else {
        $img_fondo = "fondo_normal.png"; // Mundo equilibrado
    }

    // Construimos el nombre del archivo de la mascota (ej: "planta_verde.png" o "animal_azul.png")
    $img_mascota = $mascota['tipo'] . "_" . $mascota['color'] . ".png";
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Dashboard - EcoGotchi</title>
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>

    <div class="dashboard">
        <header>
            <h1>🌱 EcoGotchi</h1>
            <div class="usuario">
                <span>Jugador: <?php echo $_SESSION["usuario_nombre"]; ?></span>
                <a href="logout.php" class="logout">Cerrar Sesión</a>
            </div>
        </header>

        <?php if (!$mascota): ?>
            <div class="crear-mascota" style="text-align:center; padding: 50px;">
                <h2>¿Aún no tienes un compañero?</h2>
                <a href="crear_mascota.php">
                    <button class="logout" style="background:#4ade80; color:#064e3b; padding:15px 30px;">Adoptar ahora</button>
                </a>
            </div>

        <?php else: ?>
            <div class="game-container">

                <div class="escenario-pet" style="background-image: url('img/<?php echo $img_fondo; ?>');">

                    <img src="img/<?php echo $img_mascota; ?>"
                        alt="Mascota"
                        class="mascota-personaje"
                        style="<?php echo ($mascota['hambre'] < 20) ? 'filter: grayscale(80%) contrast(0.8);' : ''; ?>">
                </div>

                <div class="estadisticas-grid">
                    <?php
                    $stats = [
                        'Hambre' => 'hambre',
                        'Sueño' => 'sueno',
                        'Diversión' => 'diversion',
                        'Higiene' => 'higiene'
                    ];

                    foreach ($stats as $label => $key):
                        $val = $mascota[$key];
                        // Asignamos una clase CSS según el valor para cambiar el color de la barra
                        $estado = ($val < 40) ? 'bajo' : (($val < 80) ? 'medio' : 'alto');
                    ?>
                        <div class="barra">
                            <label>
                                <span><?php echo $label; ?></span>
                                <span><?php echo $val; ?>%</span> </label>
                            <div class="progreso">
                                <div class="valor <?php echo $estado; ?>" style="width: <?php echo $val; ?>%"></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="acciones">
                    <form action="accion_mascota.php" method="POST">
                        <input type="hidden" name="accion" value="alimentar">
                        <button>🍎 COMER</button>
                    </form>

                    <form action="accion_mascota.php" method="POST">
                        <input type="hidden" name="accion" value="dormir">
                        <button>😴 DORMIR</button>
                    </form>

                    <form action="accion_mascota.php" method="POST">
                        <input type="hidden" name="accion" value="jugar">
                        <button>🎾 JUGAR</button>
                    </form>

                    <form action="accion_mascota.php" method="POST">
                        <input type="hidden" name="accion" value="limpiar">
                        <button>🛁 LIMPIAR</button>
                    </form>
                </div>

            </div>
        <?php endif; ?>
    </div>

</body>

</html>