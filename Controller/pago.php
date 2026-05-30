<?php
require_once 'auth.php';
require_once '../Model/Vehiculo.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_coche = $_POST['id_coche'] ?? null;
    $fecha_inicio = $_POST['fecha_inicio'] ?? null;
    $fecha_fin = $_POST['fecha_fin'] ?? null;

    if ($id_coche && $fecha_inicio && $fecha_fin) {
        $coche = Vehiculo::getById($id_coche);

        if ($coche) {
            // Calculo cuantos dias son para sacar el precio total
            $datetime1 = new DateTime($fecha_inicio);
            $datetime2 = new DateTime($fecha_fin);
            $interval = $datetime1->diff($datetime2);
            $dias = $interval->days + 1; // sumo 1 porque el primer dia tambien cuenta
            
            $precio_total = $dias * $coche->getPrecioDia();

            // Cargo la pagina de pago
            include '../View/reservar/pago_view.php';
        } else {
            header('Location: coches.php');
        }
    } else {
        header('Location: coches.php');
    }
} else {
    header('Location: coches.php');
}
