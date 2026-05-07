<?php
session_start();
require_once 'app/controllers/TiendaController.php';
$ctrl = new TiendaController();
$ctrl->comprar();
