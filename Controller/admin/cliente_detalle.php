<?php
require_once 'auth_admin.php';
require_once '../../Model/Cliente.php';
require_once '../../Model/Reserva.php';

$id_cliente = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id_cliente) {
    header('Location: clientes_admin.php');
    exit;
}

// Procesar formulario de sanción (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_reserva     = filter_input(INPUT_POST, 'id_reserva', FILTER_VALIDATE_INT);
    $sancion_km     = max(0, floatval($_POST['sancion_km'] ?? 0));
    $sancion_tiempo = max(0, floatval($_POST['sancion_tiempo'] ?? 0));

    if ($id_reserva) {
        Reserva::updateSancion($id_reserva, $sancion_km, $sancion_tiempo);
    }
    header('Location: cliente_detalle.php?id=' . $id_cliente . '&ok=sancion');
    exit;
}

$cliente = Cliente::getClienteById($id_cliente);
if (!$cliente) {
    header('Location: clientes_admin.php');
    exit;
}

$reservas = Reserva::getReservasByCliente($id_cliente);
$paginaActiva = 'clientes';
include '../../View/admin/cliente_detalle_view.php';
