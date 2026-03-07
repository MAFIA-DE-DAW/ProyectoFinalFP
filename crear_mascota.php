<?php
require_once "includes/proteger.php";
require_once "includes/conexion.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nombre = $_POST["nombre"];
    $tipo = $_POST["tipo"];
    $color = $_POST["color"];

    try {
        // Añadidos: tipo, color y salud. Valores iniciales a 100
        $sql = "INSERT INTO mascotas (id_usuario, nombre, tipo, color, hambre, sueno, diversion, higiene, salud, fecha_ultima_actualizacion) 
                VALUES (:id_usuario, :nombre, :tipo, :color, 100, 100, 100, 100, 100, NOW())";

        $consulta_crear_mascota = $bd->prepare($sql);

        $consulta_crear_mascota->execute([
            ':id_usuario' => $_SESSION["usuario_id"],
            ':nombre' => $nombre,
            ':tipo' => $tipo,
            ':color' => $color
        ]);

        // volver al dashboard
        header("Location: dashboard.php");
        exit();
    } catch (PDOException $e) {
        echo "Error al crear la mascota: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>

<head>

    <title>Crear Mascota</title>

    <link rel="stylesheet" href="css/styles.css">

</head>

<body>

    <div class="form-container">

        <h2>Crea tu EcoMascota 🌱</h2>

        <form method="POST">

            <label>Nombre de tu mascota</label>
            <input type="text" name="nombre" required>

            <label>Tipo de mascota</label>

            <select name="tipo">

                <option value="planta">🌱 Planta</option>
                <option value="animal">🐾 Animal</option>
                <option value="fantasia">✨ Fantasía</option>

            </select>

            <label>Color</label>

            <select name="color">

                <option value="verde">Verde</option>
                <option value="morado">Morado</option>
                <option value="azul">Azul</option>

            </select>

            <button type="submit">Crear Mascota</button>

        </form>

    </div>

</body>

</html>