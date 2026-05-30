<?php
// Arrancamos la sesion para poder usar las variables de $_SESSION
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Si el usuario no ha iniciado sesion lo mandamos al login
if (!isset($_SESSION['id_cliente'])) {
    header('Location: ../Controller/login.php');
    exit;
}
?>