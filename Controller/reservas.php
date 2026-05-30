<?php
require_once 'auth.php';
require_once '../Model/Reserva.php';

$id_cliente = $_SESSION['id_cliente'];
$todasLasReservas = Reserva::getReservasByCliente($id_cliente);

$activas = [];
$anteriores = [];
$fecha_actual = date('Y-m-d');

foreach ($todasLasReservas as $reserva) {
    if ($reserva->getEstado() !== 'Cancelada' && $reserva->getFechaFin() >= $fecha_actual) {
        $activas[] = $reserva;
    } else {
        $anteriores[] = $reserva;
    }
}

// Cargar la vista de mis reservas
include '../View/reservar/mis_reservas_view.php';
?>
