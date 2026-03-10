<?php
// --- 1. SEGURIDAD Y CONEXIÓN ---
require_once "includes/proteger.php"; // Verifica que el usuario haya iniciado sesión
require_once "includes/conexion.php"; // Conecta a la base de datos MySQL

$id_usuario = $_SESSION["usuario_id"]; // ID del usuario actual

// --- 2. OBTENCIÓN DE DATOS DE LA MASCOTA ---
$sql = "SELECT * FROM mascotas WHERE id_usuario = :id_usuario";
$consulta_mascota = $bd->prepare($sql);
$consulta_mascota->execute([':id_usuario' => $id_usuario]);
$mascota = $consulta_mascota->fetch(PDO::FETCH_ASSOC); // false si no hay mascota

// --- 2.1. COMPROBAR MISIONES DEL DÍA ---
$sql_check = "
SELECT COUNT(*) 
FROM misiones_completadas
WHERE id_usuario = :usuario
AND DATE(fecha) = CURDATE()
";
$consulta_check = $bd->prepare($sql_check);
$consulta_check->execute([':usuario' => $id_usuario]);
$misiones_hoy = $consulta_check->fetchColumn();

// --- 2.2. SI NO HIZO MISIONES, BAJAR ECO ---
if ($misiones_hoy == 0) {
    $sql_bajar = "UPDATE entorno SET nivel_ecologico = GREATEST(0, nivel_ecologico - 5) WHERE id_usuario = :usuario";
    $bd->prepare($sql_bajar)->execute([':usuario' => $id_usuario]);
}

// --- 2.3. ENTORNO ECOLÓGICO ---
$sql_entorno = "SELECT * FROM entorno WHERE id_usuario = :id";
$consulta_entorno = $bd->prepare($sql_entorno);
$consulta_entorno->execute([':id' => $id_usuario]);
$entorno = $consulta_entorno->fetch(PDO::FETCH_ASSOC);

// Si no hay entorno, crearlo
if (!$entorno) {
    $sql_crear = "INSERT INTO entorno (id_usuario,nivel_ecologico,estado_entorno) VALUES (:id,50,'normal')";
    $bd->prepare($sql_crear)->execute([':id' => $id_usuario]);
    $entorno = ["nivel_ecologico" => 50, "estado_entorno" => "normal"];
}

// --- 3. LÓGICA DE TIEMPO REAL (DEGRADACIÓN) ---
if ($mascota) {
    $ultima = strtotime($mascota["fecha_ultima_actualizacion"]);
    $ahora = time();
    $diferencia_segundos = $ahora - $ultima;
    $minutos = floor($diferencia_segundos / 60);

    if ($minutos > 0) {
        $hambre = max(0, $mascota["hambre"] - ($minutos * 2));
        $sueno = max(0, $mascota["sueno"] - ($minutos * 1));
        $diversion = max(0, $mascota["diversion"] - ($minutos * 2));
        $higiene = max(0, $mascota["higiene"] - ($minutos * 1));

        // --- COMPROBAR MUERTE ---
        if ($hambre == 0 || $sueno == 0 || $diversion == 0 || $higiene == 0) {
            $_SESSION['mascota_muerta'] = $mascota["nombre"]; // Guardar nombre

            // Eliminamos la mascota
            $sql_muerte = "DELETE FROM mascotas WHERE id_usuario = :id";
            $bd->prepare($sql_muerte)->execute([':id' => $id_usuario]);

            $mascota = false; // Indica que no hay mascota viva

            // --- REINICIAR ECO ---
            $sql_reset_eco = "UPDATE entorno 
                      SET nivel_ecologico = 50,
                          estado_entorno = 'normal',
                          fecha_ultima_actualizacion = NOW()
                      WHERE id_usuario = :usuario";
            $bd->prepare($sql_reset_eco)->execute([':usuario' => $id_usuario]);

            // Actualizamos la variable local
            $entorno['nivel_ecologico'] = 50;
            $entorno['estado_entorno'] = 'normal';
        }

        // Guardar stats actualizadas
        if ($mascota) {
            $sql_update = "UPDATE mascotas SET hambre=:h, sueno=:s, diversion=:d, higiene=:hi, fecha_ultima_actualizacion=NOW() WHERE id_usuario=:id";
            $bd->prepare($sql_update)->execute([
                ':h' => $hambre,
                ':s' => $sueno,
                ':d' => $diversion,
                ':hi' => $higiene,
                ':id' => $id_usuario
            ]);
            $mascota["hambre"] = $hambre;
            $mascota["sueno"] = $sueno;
            $mascota["diversion"] = $diversion;
            $mascota["higiene"] = $higiene;
        }
    }
}

// --- 4. LÓGICA VISUAL ---
$nivel_eco = $entorno['nivel_ecologico'];
switch (true) {
    case ($nivel_eco > 70):
        $estado_entorno = "verde";
        $img_fondo = "fondo_bueno.png";
        break;
    case ($nivel_eco >= 50):
        $estado_entorno = "normal";
        $img_fondo = "fondo_normal.png";
        break;
    case ($nivel_eco >= 21):
        $estado_entorno = "malo";
        $img_fondo = "fondo_malo.png";
        break;
    default:
        $estado_entorno = "extremo";
        $img_fondo = "fondo_chungo.png";
        break;
}

// Color barra eco
if ($nivel_eco > 50) {
    $ratio = ($nivel_eco - 50) / 50;
    $r = 255 * (1 - $ratio);
    $g = 255;
    $b = 0;
} elseif ($nivel_eco > 20) {
    $ratio = ($nivel_eco - 20) / 30;
    $r = 255;
    $g = 255 * $ratio;
    $b = 0;
} else {
    $r = 180;
    $g = 0;
    $b = 0;
}
$color_barra = "rgb($r,$g,$b)";

// Después de determinar color y fondo, se calcula degradación automática
$ultima_eco = strtotime($entorno["fecha_ultima_actualizacion"]);
$diferencia_segundos = $ahora - $ultima_eco;
$minutos = floor($diferencia_segundos / 60);

// degradación automática, por ejemplo 1 punto por minuto
if ($minutos > 0) {
    $nivel_eco = max(0, $entorno["nivel_ecologico"] - $minutos);
    $sql_update_eco = "UPDATE entorno 
                       SET nivel_ecologico = :nivel, fecha_ultima_actualizacion = NOW()
                       WHERE id_usuario = :usuario";
    $bd->prepare($sql_update_eco)->execute([
        ':nivel' => $nivel_eco,
        ':usuario' => $id_usuario
    ]);
}

// Imagen mascota (solo si está viva)
if ($mascota && isset($mascota['tipo'], $mascota['color'])) {
    $img_mascota = $mascota['tipo'] . "_" . $mascota['color'] . ".png";
} else {
    $img_mascota = null;
}

// Mensaje mascota
$mensaje = "";
if ($mascota) {
    if ($mascota["hambre"] < 30) $mensaje = ["¡Tengo mucha hambre! 🍎", "Mi barriga ruge... 🍔", "¿Me das algo de comer?"][array_rand(["¡Tengo mucha hambre! 🍎", "Mi barriga ruge... 🍔", "¿Me das algo de comer?"])];
    elseif ($mascota["sueno"] < 30) $mensaje = ["Tengo mucho sueño... 😴", "Necesito dormir un poco", "Estoy muy cansado..."][array_rand(["Tengo mucho sueño... 😴", "Necesito dormir un poco", "Estoy muy cansado..."])];
    elseif ($mascota["diversion"] < 30) $mensaje = ["¡Quiero jugar! 🎾", "Estoy aburrido...", "¿Jugamos un rato?"][array_rand(["¡Quiero jugar! 🎾", "Estoy aburrido...", "¿Jugamos un rato?"])];
    elseif ($mascota["higiene"] < 30) $mensaje = ["Necesito un baño 🛁", "Estoy muy sucio...", "Hora de limpiarme"][array_rand(["Necesito un baño 🛁", "Estoy muy sucio...", "Hora de limpiarme"])];
    else $mensaje = ["¡Estoy genial! 🌱", "Me siento muy bien hoy", "Gracias por cuidarme"][array_rand(["¡Estoy genial! 🌱", "Me siento muy bien hoy", "Gracias por cuidarme"])];
}

// --- 5. MISIONES ---
$sql_misiones = "
SELECT * FROM misiones
WHERE id NOT IN (
    SELECT id_mision FROM misiones_completadas
    WHERE id_usuario=:usuario AND DATE(fecha)=CURDATE()
)
ORDER BY RAND() LIMIT 3";
$consulta = $bd->prepare($sql_misiones);
$consulta->execute([':usuario' => $id_usuario]);
$misiones = $consulta->fetchAll(PDO::FETCH_ASSOC);

// --- 6. API DEL TIEMPO ---
$apiKey = "2b38874df3e4f0aab602b288b36e2fe2";
$ciudad = "Madrid";
$url = "https://api.openweathermap.org/data/2.5/weather?q=$ciudad&appid=$apiKey&units=metric&lang=es";
$respuesta = @file_get_contents($url);
$clase_clima = "clima-normal";
if ($respuesta !== false) {
    $datos = json_decode($respuesta, true);
    $clima = $datos["weather"][0]["main"];
    $lluvia = isset($datos["rain"]["1h"]) ? $datos["rain"]["1h"] : 0;
    if ($lluvia > 0 || in_array($clima, ["Rain", "Drizzle", "Mist", "Haze"])) $clase_clima = "clima-lluvia";
    elseif ($clima === "Clear") $clase_clima = "clima-sol";
    elseif ($clima === "Clouds") $clase_clima = "clima-nubes";
    elseif ($clima === "Thunderstorm") $clase_clima = "clima-tormenta";
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Dashboard - EcoGotchi</title>
    <link rel="stylesheet" href="css/styles.css">
    <script src="js/funciones.js"></script>
</head>

<body class="dashboard-body">

    <div class="dashboard">

        <header>
            <h1>🌱 EcoGotchi</h1>
            <div class="usuario">
                <span>Jugador: <?php echo $_SESSION["usuario_nombre"]; ?></span>
                <a href="logout.php" class="logout">Cerrar Sesión</a>
            </div>
        </header>

        <div class="layout-juego">

            <?php if (!$mascota && !isset($_SESSION['mascota_muerta'])): ?>
                <div class="crear-mascota" style="text-align:center; padding:50px;">
                    <h2>¿Aún no tienes un compañero?</h2>
                    <a href="crear_mascota.php">
                        <button class="logout" style="background:#4ade80;color:#064e3b;padding:15px 30px;">Adoptar ahora</button>
                    </a>
                </div>
            <?php else: ?>
                <div class="layout-principal">
                    <div class="columna-juego">

                        <div class="zona-juego">
                            <div class="game-container">

                                <!-- ESCENARIO -->
                                <div class="escenario-pet <?php echo $clase_clima; ?> estado-<?php echo $estado_entorno; ?>" style="background-image:url('img/<?php echo $img_fondo; ?>');">
                                    <div class="clima-overlay"></div>

                                    <!-- NOMBRE ARRIBA (solo si está viva) -->
                                    <?php if ($mascota): ?>
                                        <div class="cartel-nombre">
                                            <?php echo htmlspecialchars($mascota['nombre']); ?>
                                        </div>
                                    <?php endif; ?>

                                    <!-- BOCADILLO -->
                                    <?php if (!empty($mensaje) && !$mascota): else: ?>
                                        <div class="bocadillo"><?php echo $mensaje; ?></div>
                                    <?php endif; ?>

                                    <!-- MASCOTA O TUMBA -->
                                    <?php if (isset($_SESSION['mascota_muerta'])): ?>
                                        <div class="tumba-escenario">
                                            <div class="tumba">🪦</div>
                                            <div class="nombre-tumba"><?php echo htmlspecialchars($_SESSION['mascota_muerta']); ?></div>
                                            <div class="flores"><span>🌸</span><span>🌼</span><span>🌺</span></div>
                                            <div style="margin-top:15px;">
                                                <a href="crear_mascota.php">
                                                    <button class="btn-accion">
                                                        Adoptar nueva mascota
                                                    </button>
                                                </a>
                                            </div>
                                        </div>
                                        <?php unset($_SESSION['mascota_muerta']); ?>
                                    <?php elseif ($mascota && $img_mascota): ?>
                                        <img src="img/<?php echo $img_mascota; ?>" alt="Mascota" class="mascota-personaje">
                                    <?php endif; ?>
                                </div> <!-- FIN ESCENARIO -->

                            </div>
                        </div>

                        <!-- ACCIONES -->
                        <div class="acciones">
                            <form action="accion_mascota.php" method="POST"><input type="hidden" name="accion" value="alimentar"><button>🍎 COMER</button></form>
                            <form action="accion_mascota.php" method="POST"><input type="hidden" name="accion" value="dormir"><button>😴 DORMIR</button></form>
                            <form action="accion_mascota.php" method="POST"><input type="hidden" name="accion" value="jugar"><button>🎾 JUGAR</button></form>
                            <form action="accion_mascota.php" method="POST"><input type="hidden" name="accion" value="limpiar"><button>🛁 LIMPIAR</button></form>
                        </div>

                        <!-- ECO BARRA -->
                        <div class="eco-barra">
                            <h2>🌍 ECO BARRA</h2>
                            <div class="barra-eco">
                                <div class="nivel brillo" style="width:<?php echo $nivel_eco ?>%;background-color:<?php echo $color_barra; ?>;">
                                    <?php echo $nivel_eco; ?>%
                                </div>
                            </div>
                        </div>

                        <!-- ESTADÍSTICAS -->
                        <div class="estadisticas-grid">
                            <?php
                            $stats = ['Hambre' => 'hambre', 'Sueño' => 'sueno', 'Diversión' => 'diversion', 'Higiene' => 'higiene'];
                            foreach ($stats as $label => $key):
                                if ($mascota) $val = $mascota[$key];
                                else $val = 0;
                                $estado = ($val < 40) ? 'bajo' : (($val < 80) ? 'medio' : 'alto');
                            ?>
                                <div class="barra">
                                    <label><span><?php echo $label; ?></span><span><?php echo $val; ?>%</span></label>
                                    <div class="progreso">
                                        <div class="valor <?php echo $estado; ?>" style="width:<?php echo $val; ?>%"></div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                    </div> <!-- FIN COLUMNA IZQUIERDA -->

                    <!-- COLUMNA DERECHA (MISIONES) -->
                    <div class="panel-misiones">
                        <h2>🌍 Misiones ecológicas</h2>
                        <div class="misiones">
                            <?php foreach ($misiones as $mision): ?>
                                <form action="completar_mision.php" method="POST">
                                    <input type="hidden" name="mision_id" value="<?php echo $mision["id"]; ?>">
                                    <div class="mision">
                                        <h3><?php echo $mision["titulo"]; ?></h3>
                                        <p><?php echo $mision["descripcion"]; ?></p>
                                        <button type="button" onclick="resolverMision(<?php echo $mision['id']; ?>)">Resolver misión</button>
                                    </div>
                                </form>
                            <?php endforeach; ?>
                        </div>
                    </div>

                </div> <!-- FIN LAYOUT PRINCIPAL -->
            <?php endif; ?>

        </div>
    </div>