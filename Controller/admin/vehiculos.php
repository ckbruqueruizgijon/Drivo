<?php
require_once 'auth_admin.php';
require_once '../../Model/Vehiculo.php';

// ───── POST: toggle oferta, toggle disponible o eliminar ─────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $id     = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

    if ($id) {
        if ($action === 'toggle_oferta') {
            Vehiculo::toggleOferta($id);
        } elseif ($action === 'toggle_disponible') {
            $coche = Vehiculo::getById($id);
            if ($coche) {
                Vehiculo::setDisponibilidad($id, $coche->getDisponible() ? 0 : 1);
            }
        } elseif ($action === 'delete') {
            $eliminado = Vehiculo::deleteById($id);
            header('Location: vehiculos.php?ok=' . ($eliminado ? 'eliminado' : 'error_delete'));
            exit;
        }
    }
    header('Location: vehiculos.php');
    exit;
}

// ───── GET: mostrar tabla ─────
$coches = Vehiculo::getAll();

// Mensajes de feedback
$msg = $_GET['ok'] ?? null;

$paginaActiva = 'vehiculos';
include '../../View/admin/vehiculos_view.php';
