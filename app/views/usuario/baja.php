<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Darse de baja - EcoGotchi</title>

    <link rel="stylesheet" href="assets/css/styles.css">
</head>

<body class="dashboard-body">

    <div class="mi-cuenta-container">

        <div class="mi-cuenta-panel baja-panel">

            <div class="mi-cuenta-header">
                <h1>⚠ Darse de baja</h1>

                <a href="mi_cuenta.php" class="btn-volver-cuenta">
                    Cancelar
                </a>
            </div>

            <p class="texto-baja">
                Esta acción eliminará permanentemente:
            </p>

            <ul class="lista-baja">
                <li>Tu cuenta</li>
                <li>Tu mascota actual</li>
                <li>Tu historial de mascotas</li>
                <li>Tus misiones completadas</li>
                <li>Tus compras ecológicas</li>
            </ul>

            <div class="acciones-baja">

                <form method="POST">

                    <button type="submit" class="btn-baja">
                        Confirmar baja
                    </button>

                </form>

            </div>

        </div>

    </div>

</body>

</html>