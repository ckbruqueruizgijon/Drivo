<?php
require_once 'auth.php';
require_once '../Model/Reserva.php';
require_once '../Model/Cliente.php';
require_once '../Model/Vehiculo.php';
require_once '../Model/DriveMailer.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_coche     = $_POST['id_coche']     ?? null;
    $fecha_inicio = $_POST['fecha_inicio'] ?? null;
    $fecha_fin    = $_POST['fecha_fin']    ?? null;
    $precio_total = $_POST['precio_total'] ?? null;

    if ($id_coche && $fecha_inicio && $fecha_fin && $precio_total) {
        $id_cliente = $_SESSION['id_cliente'];

        // Compruebo que las fechas no esten ya cogidas por otra reserva
        $reservasActivas = Reserva::getReservasActivasByVehiculo($id_coche);
        foreach ($reservasActivas as $res) {
            if ($fecha_inicio <= $res['fin'] && $fecha_fin >= $res['inicio']) {
                $_SESSION['mensaje_error'] = "Las fechas seleccionadas ya han sido reservadas por otro usuario.";
                header("Location: reservar.php?id=" . $id_coche);
                exit;
            }
        }

        // Creo la reserva y la guardo
        $reserva = new Reserva($id_coche, $id_cliente, $fecha_inicio, $fecha_fin, $precio_total, 'Activa');

        if ($reserva->insert()) {
            // Enviamos correo de confirmación al cliente
            $cliente = Cliente::getClienteById($id_cliente);
            $vehiculo = Vehiculo::getById($id_coche);
            if ($cliente && $vehiculo) {
                DriveMailer::enviarConfirmacionReserva(
                    $cliente->getEmail(),
                    $cliente->getNombre(),
                    $vehiculo->getNombreCompleto(),
                    $fecha_inicio,
                    $fecha_fin,
                    (float) $precio_total
                );
            }

            $_SESSION['mensaje_exito'] = "Pago realizado con éxito. Tu reserva ha sido confirmada.";
            header('Location: reservas.php');
            exit;
        } else {
            $_SESSION['mensaje_error'] = "Hubo un error al procesar tu reserva. Inténtalo de nuevo.";
            header("Location: index.php");
            exit;
        }
    } else {
        header('Location: index.php');
        exit;
    }
} else {
    header('Location: index.php');
    exit;
}
