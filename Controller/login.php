<?php
require_once '../Model/Cliente.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
} // Necesario para mantener al usuario conectado

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recogemos el usuario (o email) y la contraseña del formulario
    $user_input = trim($_POST['email']); 
    $pass_input = $_POST['password'];

    // Usamos el método estático de tu clase Cliente
    $cliente = Cliente::login($user_input, $pass_input);

    if ($cliente) {
        // Si ha entrado bien guardamos sus datos en la sesion
        $_SESSION['id_cliente'] = $cliente->getId();
        $_SESSION['nombre'] = $cliente->getNombre();
        $_SESSION['rol'] = $cliente->getRol();
        
        // Lo mandamos a la pagina principal
        header('Location: ../index.php');
        exit;
    } else {
        // Si el login falla volvemos con un error
        header('Location: login.php?error=auth');
        exit;
    }
} else {
    // Si entra por GET mostramos el formulario
    include '../View/login/login_view.php';
}