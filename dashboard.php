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

        // --- COMPROBAR SI LA MASCOTA MUERE ---
        if ($hambre == 0 || $sueno == 0 || $diversion == 0 || $higiene == 0) {

            // Eliminamos la mascota de la base de datos
            $sql_muerte = "DELETE FROM mascotas WHERE id_usuario = :id";
            $bd->prepare($sql_muerte)->execute([
                ':id' => $_SESSION["usuario_id"]
            ]);

            // Redirigimos para que aparezca la opción de crear otra
            header("Location: dashboard.php?mascota=muerta");
            exit;
        }

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
// API DEL TIEMPO
$apiKey = "2b38874df3e4f0aab602b288b36e2fe2";
$ciudad = "Madrid";

$url = "https://api.openweathermap.org/data/2.5/weather?q=$ciudad&appid=$apiKey&units=metric&lang=es";

$respuesta = file_get_contents($url);
$datos = json_decode($respuesta, true);

$clima = $datos["weather"][0]["main"];

$clase_clima = "";

switch ($clima) {

    case "Clear":
        $clase_clima = "clima-sol";
        break;

    case "Rain":
        $clase_clima = "clima-lluvia";
        break;

    case "Clouds":
        $clase_clima = "clima-nubes";
        break;

    case "Thunderstorm":
        $clase_clima = "clima-tormenta";
        break;

    default:
        $clase_clima = "clima-normal";
}
?>

<?php if (isset($_GET["mascota"]) && $_GET["mascota"] == "muerta"): ?>
    <div style="background:#6b21a8;padding:15px;text-align:center;">
        💀 Tu mascota murió por falta de cuidados.
    </div>
<?php endif; ?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Dashboard - EcoGotchi</title>
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>

    <div class="dashboard">
        <!-- CABECERA DEL JUEGO -->
        <header>
            <h1>🌱 EcoGotchi</h1>
            <div class="usuario">
                <span>Jugador: <?php echo $_SESSION["usuario_nombre"]; ?></span>
                <a href="logout.php" class="logout">Cerrar Sesión</a>
            </div>
        </header>

        <!-- EN CASO DE NO TERNER MASCOTA, LA CREAMOS -->
        <?php if (!$mascota): ?>
            <div class="crear-mascota" style="text-align:center; padding: 50px;">
                <h2>¿Aún no tienes un compañero?</h2>
                <a href="crear_mascota.php">
                    <button class="logout" style="background:#4ade80; color:#064e3b; padding:15px 30px;">Adoptar ahora</button>
                </a>
            </div>

            <!-- EN CASO DE TENER MASCOTA LA MOSTRAMOS -->
        <?php else: ?>

            <!-- CONTENEDOR DEL JUEGO -->
            <div class="game-container">

                <!-- CARGAMOS FONDO -->
                <div class="escenario-pet <?php echo $clase_clima; ?>"
                    style="background-image: url('img/<?php echo $img_fondo; ?>');">

                    <div class="clima-overlay"></div>

                    <!-- CARTEL CON EL NOMBRE -->
                    <div class="cartel-nombre">
                        <?php echo htmlspecialchars($mascota["nombre"]); ?>
                    </div>

                    <!-- BOCADILLO DE LA MASCOTA -->
                    <?php
                    $mensaje = "";

                    if ($mascota["hambre"] < 30) {
                        $mensaje = "Tengo hambre 🍎";
                    } elseif ($mascota["sueno"] < 30) {
                        $mensaje = "Tengo sueño 😴";
                    } elseif ($mascota["diversion"] < 30) {
                        $mensaje = "Quiero jugar 🎾";
                    } elseif ($mascota["higiene"] < 30) {
                        $mensaje = "Estoy sucio 🛁";
                    }
                    ?>

                    <?php if ($mensaje != ""): ?>
                        <div class="bocadillo">
                            <?php echo $mensaje; ?>
                        </div>
                    <?php endif; ?>

                    <!-- MASCOTA (IMAGEN) -->
                    <img src="img/<?php echo $img_mascota; ?>"
                        alt="Mascota"
                        class="mascota-personaje"
                        style="<?php
                                $filtro = "";
                                if ($mascota["hambre"] < 20 || $mascota["sueno"] < 20) {
                                    $filtro = "filter: grayscale(80%) contrast(0.8);";
                                } elseif ($mascota["diversion"] > 80) {
                                    $filtro = "filter: brightness(1.2);";
                                }
                                ?>">
                </div>
            </div>
            <!-- ESTADISTICAS Y BARRAS -->
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
            <!-- ACCIONES -->
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