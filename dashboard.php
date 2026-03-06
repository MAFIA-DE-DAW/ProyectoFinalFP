<?php
// Incluye el archivo que comprueba que el usuario esté logueado.
// Si no lo está, normalmente redirige al login.
require_once "includes/proteger.php";

// Incluye la conexión a la base de datos (PDO)
require_once "includes/conexion.php";


// CONSULTA PARA OBTENER LA MASCOTA DEL USUARIO LOGUEADO

// Creamos la consulta SQL para buscar la mascota
// que pertenece al usuario actual (guardado en la sesión)
$sql = "SELECT * FROM mascotas WHERE id_usuario = :id_usuario";

// Preparamos la consulta para evitar inyección SQL
$consulta_mascota = $bd->prepare($sql);

// Ejecutamos la consulta enviando el id del usuario logueado
$consulta_mascota->execute([
    ':id_usuario' => $_SESSION["usuario_id"]
]);

// Guardamos el resultado de la consulta en un array asociativo
// Si el usuario no tiene mascota devolverá FALSE
$mascota = $consulta_mascota->fetch(PDO::FETCH_ASSOC);

// ===============================
// SISTEMA DE DEGRADACIÓN
// ===============================

if ($mascota) {

    // Convertimos la fecha de la BD a timestamp
    $ultima = strtotime($mascota["ultima_actualizacion"]);

    // Hora actual
    $ahora = time();

    // Calculamos minutos que han pasado
    $minutos = floor(($ahora - $ultima) / 60);

    // Si ha pasado al menos 1 minuto degradamos
    if ($minutos > 0) {

        // Bajamos estadísticas según el tiempo
        $hambre = max(0, $mascota["hambre"] - ($minutos * 2));
        $sueno = max(0, $mascota["sueno"] - ($minutos * 1));
        $diversion = max(0, $mascota["diversion"] - ($minutos * 2));
        $higiene = max(0, $mascota["higiene"] - ($minutos * 1));

        // Actualizamos la base de datos
        $sql_update = "UPDATE mascotas SET
            hambre = :hambre,
            sueno = :sueno,
            diversion = :diversion,
            higiene = :higiene,
            ultima_actualizacion = NOW()
            WHERE id_usuario = :id_usuario";

        $consulta_update = $bd->prepare($sql_update);

        $consulta_update->execute([
            ':hambre' => $hambre,
            ':sueno' => $sueno,
            ':diversion' => $diversion,
            ':higiene' => $higiene,
            ':id_usuario' => $_SESSION["usuario_id"]
        ]);

        // Actualizamos también el array local
        $mascota["hambre"] = $hambre;
        $mascota["sueno"] = $sueno;
        $mascota["diversion"] = $diversion;
        $mascota["higiene"] = $higiene;
    }
}
?>

<!DOCTYPE html>
<html>

<head>

    <!-- Título de la página -->
    <title>EcoGotchi</title>

    <!-- Archivo CSS del proyecto -->
    <link rel="stylesheet" href="css/styles.css">

</head>

<body>

    <!-- CONTENEDOR PRINCIPAL DEL DASHBOARD -->
    <div class="dashboard">

        <header>

            <!-- Título del juego -->
            <h1>🌱 EcoGotchi</h1>

            <div class="usuario">

                <!-- Mostramos el nombre del usuario guardado en sesión -->
                <span>Jugador: <?php echo $_SESSION["usuario_nombre"]; ?></span>

                <!-- Botón para cerrar sesión -->
                <a href="logout.php">
                    <button class="logout">Cerrar sesión</button>
                </a>

            </div>

        </header>


        <!-- COMPROBAMOS SI EL USUARIO NO TIENE MASCOTA -->
        <?php if (!$mascota): ?>

            <div class="crear-mascota">

                <h2>No tienes mascota todavía</h2>

                <!-- Botón que lleva a la página para crear mascota -->
                <a href="crear_mascota.php">
                    <button>Crear Mascota</button>
                </a>

            </div>

            <!-- SI EL USUARIO YA TIENE MASCOTA SE MUESTRA EL JUEGO -->
        <?php else: ?>

            <div class="game-container">

                <!-- INFORMACIÓN DE LA MASCOTA -->
                <div class="mascota">

                    <!-- Mostramos el nombre de la mascota -->
                    <h2><?php echo $mascota["nombre"]; ?></h2>

                    <!-- Icono o imagen de la mascota -->
                    <div class="mascota-img">
                        🐣
                    </div>

                </div>



                <!-- BARRAS DE ESTADÍSTICAS -->
                <div class="estadisticas">

                    <!-- BARRA DE HAMBRE -->
                    <div class="barra">
                        <label>Hambre</label>
                        <div class="progreso">

                            <!-- 
                            La clase cambia según el nivel:
                            bajo = rojo
                            medio = amarillo
                            alto = verde
                            -->
                            <div class="valor 
            <?php
            if ($mascota["hambre"] < 40) echo "bajo";
            elseif ($mascota["hambre"] < 80) echo "medio";
            else echo "alto";
            ?>"
                                style="width: <?php echo $mascota["hambre"]; ?>%">
                            </div>
                        </div>
                    </div>

                    <!-- BARRA DE SUEÑO -->
                    <div class="barra">
                        <label>Sueño</label>
                        <div class="progreso">
                            <div class="valor 
            <?php
            if ($mascota["sueno"] < 40) echo "bajo";
            elseif ($mascota["sueno"] < 80) echo "medio";
            else echo "alto";
            ?>"
                                style="width: <?php echo $mascota["sueno"]; ?>%">
                            </div>
                        </div>
                    </div>

                    <!-- BARRA DE DIVERSIÓN -->
                    <div class="barra">
                        <label>Diversión</label>
                        <div class="progreso">
                            <div class="valor 
            <?php
            if ($mascota["diversion"] < 40) echo "bajo";
            elseif ($mascota["diversion"] < 80) echo "medio";
            else echo "alto";
            ?>"
                                style="width: <?php echo $mascota["diversion"]; ?>%">
                            </div>
                        </div>
                    </div>

                    <!-- BARRA DE HIGIENE -->
                    <div class="barra">
                        <label>Higiene</label>
                        <div class="progreso">
                            <div class="valor 
            <?php
            if ($mascota["higiene"] < 40) echo "bajo";
            elseif ($mascota["higiene"] < 80) echo "medio";
            else echo "alto";
            ?>"
                                style="width: <?php echo $mascota["higiene"]; ?>%">
                            </div>
                        </div>
                    </div>

                </div>


                <!-- BOTONES DE ACCIONES DEL JUEGO -->
                <div class="acciones">

                    <!-- Cada botón es un formulario que envía una acción -->
                    <!-- El valor se envía a accion_mascota.php -->

                    <!-- Alimentar -->
                    <form action="accion_mascota.php" method="POST">
                        <input type="hidden" name="accion" value="alimentar">
                        <button>🍎 Alimentar</button>
                    </form>

                    <!-- Dormir -->
                    <form action="accion_mascota.php" method="POST">
                        <input type="hidden" name="accion" value="dormir">
                        <button>😴 Dormir</button>
                    </form>

                    <!-- Jugar -->
                    <form action="accion_mascota.php" method="POST">
                        <input type="hidden" name="accion" value="jugar">
                        <button>🎾 Jugar</button>
                    </form>

                    <!-- Limpiar -->
                    <form action="accion_mascota.php" method="POST">
                        <input type="hidden" name="accion" value="limpiar">
                        <button>🛁 Limpiar</button>
                    </form>

                </div>

            </div>

        <?php endif; ?>

    </div>

</body>

</html>