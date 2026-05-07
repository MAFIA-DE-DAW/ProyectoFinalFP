<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Mi cuenta - EcoGotchi</title>
    <link rel="stylesheet" href="<?= ASSETS_URL ?>/css/styles.css">
</head>

<body class="dashboard-body">

    <div class="mi-cuenta-container">

        <div class="mi-cuenta-panel">

            <div class="mi-cuenta-header">

                <h1>👤 Mi cuenta</h1>

                <div class="usuario">

                    <a href="dashboard.php" class="btn-volver-cuenta">
                        Volver
                    </a>

                    <a href="baja_usuario.php" class="btn-baja">
                        Darse de baja
                    </a>

                </div>

            </div>

            <h2>📜 Historial de mascotas</h2>

            <?php if (empty($historial)) : ?>

                <p class="texto-vacio">
                    Todavía no tienes mascotas en el historial.
                </p>

            <?php else : ?>
                <div class="tabla-responsive">
                    <table class="tabla-historial">

                        <tr>
                            <th>Nombre</th>
                            <th>Tipo</th>
                            <th>Color</th>
                            <th>Hambre</th>
                            <th>Sueño</th>
                            <th>Diversión</th>
                            <th>Higiene</th>
                            <th>Estado</th>
                            <th>Fecha Muerte</th>
                        </tr>

                        <?php foreach ($historial as $mascota) : ?>

                            <tr>
                                <td><?php echo htmlspecialchars($mascota["nombre"]); ?></td>
                                <td><?php echo htmlspecialchars($mascota["tipo"]); ?></td>
                                <td><?php echo htmlspecialchars($mascota["color"]); ?></td>
                                <td><?php echo $mascota["hambre"]; ?></td>
                                <td><?php echo $mascota["sueno"]; ?></td>
                                <td><?php echo $mascota["diversion"]; ?></td>
                                <td><?php echo $mascota["higiene"]; ?></td>
                                <td>
                                    <?php echo $mascota["motivo_fin"] === "muerte" ? "Muerta" : htmlspecialchars($mascota["motivo_fin"]); ?>
                                </td>
                                <td><?php echo $mascota["fecha_fin"]; ?></td>
                            </tr>

                        <?php endforeach; ?>

                    </table>
                </div>
            <?php endif; ?>

        </div>

    </div>

</body>

</html>