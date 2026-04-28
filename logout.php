<?php
session_start();

// destruir sesión
session_destroy();

// volver al login
header("Location: login.php");
exit();