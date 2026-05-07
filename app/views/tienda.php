<?php header('Content-Type: text/html; charset=utf-8'); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Tienda Verde - EcoGotchi</title>
    <link rel="stylesheet" href="/assets/css/styles.css">
</head>
<body class="dashboard-body">
    <div class="tienda-container">
        <header class="header-tienda">
            <div class="tienda-info-header">
                <img src="/assets/img/tienda_hero.png" alt="Eco Shop" class="hero-img">
                <div>
                    <h1>Tienda Verde</h1>
                    <p>¡Tus acciones en el juego se convierten en beneficios para el planeta!</p>
                </div>
            </div>
            <div class="tienda-monedas">
                <h2>💚 <?php echo number_format($monedas); ?></h2>
                <p>Monedas Verdes disponibles</p>
                <a href="dashboard.php" class="btn-volver-tienda">← Volver al juego</a>
            </div>
        </header>

        <?php if (isset($_SESSION["mensaje_tienda"])): ?>
            <div class="alerta alerta-exito"><?php echo $_SESSION["mensaje_tienda"]; unset($_SESSION["mensaje_tienda"]); ?></div>
        <?php endif; ?>

        <?php if (isset($_SESSION["error_tienda"])): ?>
            <div class="alerta alerta-error"><?php echo $_SESSION["error_tienda"]; unset($_SESSION["error_tienda"]); ?></div>
        <?php endif; ?>

        <div class="grid-acciones">
            <?php foreach ($acciones as $accion): ?>
                <div class="card-accion">
                    <div class="icono-accion"><?php echo $accion["icono"]; ?></div>
                    <h3><?php echo $accion["titulo"]; ?></h3>
                    <p><?php echo $accion["descripcion"]; ?></p>
                    
                    <div class="impacto-info">
                        <strong>Impacto:</strong>
                        <?php echo $accion["impacto_real"]; ?>
                    </div>

                    <div class="coste-wrapper">
                        <div class="monto"><?php echo $accion["coste"]; ?> 💚</div>
                    </div>

                    <form action="comprar_accion.php" method="POST">
                        <input type="hidden" name="accion_id" value="<?php echo $accion["id"]; ?>">
                        <button type="submit" class="btn-eco-3d" <?php echo ($monedas < $accion["coste"]) ? 'disabled' : ''; ?>>
                            <?php echo ($monedas < $accion["coste"]) ? 'Saldo insuficiente' : 'Realizar contribución'; ?>
                        </button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="historial-seccion">
            <h2>📜 Tu impacto en el mundo</h2>
            <?php if (empty($historial)): ?>
                <p>Aún no has realizado ninguna contribución. ¡Completa misiones para ganar monedas!</p>
            <?php else: ?>
                <?php foreach ($historial as $item): ?>
                    <div class="item-historial">
                        <span><?php echo $item["icono"]; ?> <strong><?php echo $item["titulo"]; ?></strong></span>
                        <span style="font-size: 0.8rem; color: #666;"><?php echo date('d/m/Y H:i', strtotime($item["fecha"])); ?></span>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
