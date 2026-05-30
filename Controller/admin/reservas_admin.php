<?php
require_once 'auth_admin.php';
require_once '../../Model/Reserva.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_reserva  = filter_input(INPUT_POST, 'id_reserva', FILTER_VALIDATE_INT);
    $nuevoEstado = $_POST['estado'] ?? null;
    $estadosValidos = ['Pendiente', 'Activa', 'Finalizada', 'Cancelada'];

    if ($id_reserva && in_array($nuevoEstado, $estadosValidos)) {
        Reserva::updateEstado($id_reserva, $nuevoEstado);
    }
    header('Location: reservas_admin.php');
    exit;
}

$reservas = Reserva::getTodasReservas();
$paginaActiva = 'reservas';
include '../../View/admin/reservas_view.php';
