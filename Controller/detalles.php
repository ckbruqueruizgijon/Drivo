<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../Model/Reserva.php';
require_once '../Model/Vehiculo.php';

// Verificamos sesión
if (!isset($_SESSION['id_cliente'])) {
    header("Location: ../View/login/login_view.php"); // Ajusta según tu carpeta de login
    exit();
}

$id_reserva = $_GET['id'] ?? null;
$id_cliente = $_SESSION['id_cliente'];

if (!$id_reserva) {
    header("Location: ../View/reservar/mis_reservas_view.php");
    exit();
}

// Obtenemos las reservas del cliente
$reservas_cliente = Reserva::getReservasByCliente($id_cliente);
$reserva_detalle = null;

// Buscamos la que coincida
foreach ($reservas_cliente as $r) {
    if ($r->getId() == $id_reserva) {
        $reserva_detalle = $r;
    }
}

if ($reserva_detalle) {
    $vehiculo = $reserva_detalle->getVehiculo();
    // Cargamos la vista.
    include '../View/reservar/detalles_view.php';
} else {
    header("Location: ../View/reservar/mis_reservas_view.php");
    exit();
}