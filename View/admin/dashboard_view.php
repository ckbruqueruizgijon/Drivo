<?php
$tituloPagina = 'Dashboard';
include 'layout_top.php';
?>

<p class="page-title">Bienvenido, <?= htmlspecialchars($_SESSION['nombre']) ?> 👋</p>
<p class="page-subtitle">Resumen general del sistema — <?= date('d/m/Y') ?></p>

<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="bi bi-car-front-fill"></i></div>
            <div><div class="stat-value"><?= $totalVehiculos ?></div><div class="stat-label">Vehículos en flota</div></div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon green"><i class="bi bi-calendar-check-fill"></i></div>
            <div><div class="stat-value"><?= $reservasActivas ?></div><div class="stat-label">Reservas activas</div></div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon orange"><i class="bi bi-people-fill"></i></div>
            <div><div class="stat-value"><?= $totalClientes ?></div><div class="stat-label">Clientes registrados</div></div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon purple"><i class="bi bi-cash-stack"></i></div>
            <div><div class="stat-value"><?= number_format($ingresosTotales, 0) ?>€</div><div class="stat-label">Ingresos totales</div></div>
        </div>
    </div>
</div>

<div class="admin-table-card">
    <div class="table-card-header">
        <h5><i class="bi bi-clock-history me-2"></i>Últimas reservas</h5>
        <a href="/.Dual/Proyecto_Final.V2/Controller/admin/reservas_admin.php" class="btn btn-sm btn-outline-secondary">Ver todas</a>
    </div>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr><th>#</th><th>Cliente</th><th>Vehículo</th><th>Inicio</th><th>Fin</th><th>Total</th><th>Estado</th></tr>
            </thead>
            <tbody>
                <?php if (empty($ultimasReservas)): ?>
                    <tr><td colspan="7" class="text-center text-muted py-4">No hay reservas aún.</td></tr>
                <?php else: ?>
                    <?php foreach ($ultimasReservas as $r): ?>
                        <tr>
                            <td class="fw-bold text-muted">#<?= $r->id ?></td>
                            <td><?= htmlspecialchars($r->nombre . ' ' . $r->apellidos) ?></td>
                            <td><?= htmlspecialchars($r->marca . ' ' . $r->modelo) ?></td>
                            <td><?= date('d/m/Y', strtotime($r->fecha_inicio)) ?></td>
                            <td><?= date('d/m/Y', strtotime($r->fecha_fin)) ?></td>
                            <td class="fw-bold"><?= $r->precio_total ?>€</td>
                            <td><span class="estado-badge badge-<?= strtolower($r->estado) ?>"><?= $r->estado ?></span></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'layout_bottom.php'; ?>
