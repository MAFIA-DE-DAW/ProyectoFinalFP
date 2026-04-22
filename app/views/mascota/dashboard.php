<!DOCTYPE html>
<html lang="es">

    <head>
        <meta charset="UTF-8">
        <title>Dashboard - EcoGotchi</title>
        <link rel="stylesheet" href="/ProyectoFinalFP/assets/css/styles.css">
        <script src="/ProyectoFinalFP/assets/js/funciones.js"></script>
    </head>


    <body class="dashboard-body">
        <div class="dashboard">

            <header>
                <h1>🌱 EcoGotchi</h1>
                <div class="usuario">
                    <span>Jugador: <?php echo $_SESSION["usuario_nombre"] ?? "Usuario"; ?></span>
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
                                    <div class="escenario-pet <?php echo $clase_clima; ?> estado-<?php echo $estado_entorno; ?>" style="background-image:url('/ProyectoFinalFP/assets/img/<?php echo $img_fondo; ?>');">
                                        <!--PAPELERA  -->
                                        <?php if ($mascota): ?>
                                            <div id="contenedor-basura">
                                                <?php
                                                $opciones = ["lata.png", "hueso.png", "caca.png"];

                                                // Fijamos la semilla: Siempre dará las mismas posiciones para este usuario
                                                srand($_SESSION["usuario_id"]);

                                                for ($i = 0; $i < $mascota['basura']; $i++) {
                                                    $img_src = "/ProyectoFinalFP/assets/img/" . $opciones[array_rand($opciones)];
                                                    $randLeft = rand(5, 85);

                                                    echo "<img src='" . $img_src . "'
                                                                id='basura-" . $i . "'
                                                                draggable='true'
                                                                ondragstart='manejarDrag(event)'
                                                                style='position:absolute; left:" . $randLeft . "%; bottom:20px; width:60px; z-index: 10;'>";
                                                }
                                                srand(); // Reseteamos la semilla
                                                ?>
                                            </div>
                                            <div id="papelera-reciclaje" 
                                                 style="position: absolute; left: 20px; bottom: 40px; z-index: 100;"
                                                 ondragover="event.preventDefault()" 
                                                 ondrop="manejarDrop(event, <?php echo $mascota['basura']; ?>)">
                                                <img src="/ProyectoFinalFP/assets/img/papelera.png" width="80" alt="Papelera">
                                            </div>

                                            <script>
                                                const nivelEcoActual = <?php echo json_encode($nivel_eco); ?>;

                                            </script>
                                        <?php endif; ?>
                                        <!--FIN PAPELERA-->

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
                                            <img src="/ProyectoFinalFP/assets/img/<?php echo $img_mascota; ?>" alt="Mascota" class="mascota-personaje">
                                        <?php endif; ?>
                                    </div> <!-- FIN ESCENARIO -->

                                </div>
                            </div>

                            <!-- ACCIONES -->
                            <div class="acciones">
                                <form action="accion_mascota.php" method="POST" id="form-comer"><input type="hidden" name="accion" value="alimentar"><button type="submit" class="accion-btn" id="btn-alimentar">🍎 COMER</button></form>
                                <form action="accion_mascota.php" method="POST"><input type="hidden" name="accion" value="dormir"><button>😴 DORMIR</button></form>
                                <form action="accion_mascota.php" method="POST"><input type="hidden" name="accion" value="jugar"><button>🎾 JUGAR</button></form>
                                <form action="accion_mascota.php" method="POST"><input type="hidden" name="accion" value="duchar"><button>🛁 DUCHAR</button></form>
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
                                    if ($mascota)
                                        $val = $mascota[$key];
                                    else
                                        $val = 0;
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

    </body>

</html>