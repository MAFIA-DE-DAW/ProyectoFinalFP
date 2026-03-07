<?php
// Archivo que comprueba que el usuario está logueado
require_once "includes/proteger.php";

// Archivo que crea la conexión con la base de datos
require_once "includes/conexion.php";

// Comprobamos que el formulario se haya enviado con método POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Guardamos la acción que ha enviado el usuario (alimentar, dormir, etc)
    $accion = $_POST["accion"];

    try {

        // Consulta para obtener la mascota del usuario que está logueado
        $sql = "SELECT * FROM mascotas WHERE id_usuario = :id_usuario";

        // Preparamos la consulta para evitar inyección SQL
        $consulta = $bd->prepare($sql);

        // Ejecutamos la consulta enviando el id del usuario guardado en la sesión
        $consulta->execute([
            ':id_usuario' => $_SESSION["usuario_id"]
        ]);

        // Guardamos los datos de la mascota en un array asociativo
        $mascota = $consulta->fetch(PDO::FETCH_ASSOC);

        // Comprobamos que el usuario tenga mascota
        if ($mascota) {

            // Guardamos los valores actuales de las estadísticas de la mascota
            $hambre = $mascota["hambre"];
            $sueno = $mascota["sueno"];
            $diversion = $mascota["diversion"];
            $higiene = $mascota["higiene"];

            // Según la acción que haya elegido el usuario se modifican las estadísticas
            switch ($accion) {

                // Si el usuario alimenta a la mascota aumenta el hambre
                case "alimentar":
                    $hambre += 20;
                    break;

                // Si la mascota duerme aumenta el sueño
                case "dormir":
                    $sueno += 30;
                    break;

                // Si el usuario juega con la mascota aumenta la diversión
                case "jugar":
                    $diversion += 20;
                    break;

                // Si el usuario limpia a la mascota aumenta la higiene
                case "limpiar":
                    $higiene += 20;
                    break;
                default:
                    // Acción no válida
                    header("Location: dashboard.php");
                    exit();
            }

            // Usamos min() para asegurarnos de que ningún valor pase de 100
            $hambre = min($hambre, 100);
            $sueno = min($sueno, 100);
            $diversion = min($diversion, 100);
            $higiene = min($higiene, 100);

            // Consulta para actualizar las estadísticas de la mascota en la base de datos
            $sql_update = "UPDATE mascotas SET 
                hambre = :hambre,
                sueno = :sueno,
                diversion = :diversion,
                higiene = :higiene,
                fecha_ultima_actualizacion = NOW()
                WHERE id_usuario = :id_usuario";

            // Preparamos la consulta de actualización
            $consulta_update = $bd->prepare($sql_update);

            // Ejecutamos la actualización enviando los nuevos valores
            $consulta_update->execute([
                ':hambre' => $hambre,
                ':sueno' => $sueno,
                ':diversion' => $diversion,
                ':higiene' => $higiene,
                ':id_usuario' => $_SESSION["usuario_id"]
            ]);
        }

        // Una vez realizada la acción redirigimos al dashboard
        header("Location: dashboard.php");
        exit();
    } catch (PDOException $e) {

        // Si ocurre un error en la base de datos mostramos un mensaje
        echo "Error en la acción";
    }
}
