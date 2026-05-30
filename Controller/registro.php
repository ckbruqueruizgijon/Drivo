<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../Model/Cliente.php';
require_once '../Model/DriveMailer.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $usuario_nick = trim($_POST['email']);
    $email        = strtolower(trim($_POST['email']));
    $nombre       = trim($_POST['nombre']);
    $apellidos    = trim($_POST['apellidos']);
    $pass         = $_POST['password'];

    $nuevoCliente = new Cliente($usuario_nick, $pass, $email, $nombre, $apellidos);

    if ($nuevoCliente->insert()) {
        // Enviamos correo de bienvenida (no bloqueante si falla)
        DriveMailer::enviarBienvenida($email, $nombre);

        header('Location: login.php?registro=exito');
        exit;
    } else {
        header('Location: registro.php?error=duplicado');
        exit;
    }
} else {
    include '../View/registro/registro_view.php';
}