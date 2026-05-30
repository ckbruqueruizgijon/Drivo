<?php
require_once 'auth_admin.php';
require_once '../../Model/drivoDB.php';

$db = DrivoDB::connectDB();

$totalVehiculos  = $db->query("SELECT COUNT(*) FROM flota")->fetchColumn();
$reservasActivas = $db->query("SELECT COUNT(*) FROM reservas WHERE estado = 'Activa'")->fetchColumn();
$totalClientes   = $db->query("SELECT COUNT(*) FROM clientes WHERE rol = 'cliente'")->fetchColumn();
$ingresosTotales = $db->query("SELECT COALESCE(SUM(precio_total), 0) FROM reservas WHERE estado != 'Cancelada'")->fetchColumn();

$ultimasReservas = $db->query(
    "SELECT r.id, r.fecha_inicio, r.fecha_fin, r.precio_total, r.estado, r.fecha_reserva,
            c.nombre, c.apellidos,
            f.marca, f.modelo
     FROM reservas r
     JOIN clientes c ON r.id_cliente = c.id
     JOIN flota f    ON r.id_vehiculo = f.id
     ORDER BY r.fecha_reserva DESC LIMIT 7"
)->fetchAll(PDO::FETCH_OBJ);

$paginaActiva = 'dashboard';
include '../../View/admin/dashboard_view.php';
