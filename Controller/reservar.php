<?php
require_once 'auth.php';
require_once '../Model/Vehiculo.php';

// Capturamos el ID que viene por la URL
$id_coche = $_GET['id'] ?? null;

if ($id_coche) {
    // Obtenemos los datos de ese coche específico
    $coche = Vehiculo::getById($id_coche);

    if ($coche) {
        // Obtener las reservas activas para este coche
        require_once '../Model/Reserva.php';
        $reservasActivas = Reserva::getReservasActivasByVehiculo($id_coche);
        $reservasJson = json_encode($reservasActivas);

        // Si el coche existe, cargamos la vista que acabamos de hacer
        include '../View/reservar/reservar_view.php';
    } else {
        // Si no existe el coche, redirigimos al catálogo
        header('Location: coches.php');
    }
} else {
    header('Location: index.php');
}