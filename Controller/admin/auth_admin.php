<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Nivel 1: debe estar logueado
if (!isset($_SESSION['id_cliente'])) {
    header('Location: ../../Controller/login.php');
    exit;
}

// Nivel 2: debe ser administrador
if ($_SESSION['rol'] !== 'admin') {
    header('Location: ../../Controller/index.php');
    exit;
}
