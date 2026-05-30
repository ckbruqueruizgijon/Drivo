<?php
$tituloPagina = 'Detalle del Cliente';
include 'layout_top.php';
?>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb" style="font-size:0.85rem">
        <li class="breadcrumb-item"><a href="/.Dual/Proyecto_Final.V2/Controller/admin/clientes_admin.php">Clientes</a></li>
        <li class="breadcrumb-item active"><?= htmlspecialchars($cliente->getNombre() . ' ' . $cliente->getApellidos()) ?></li>
    </ol>
</nav>

<?php if (isset($_GET['ok'])): ?>
    <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i> Sanción aplicada correctamente.
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- Ficha del cliente -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="admin-table-card p-4">
            <div class="text-center mb-3">
                <div class="stat-icon blue mx-auto mb-3" style="width:64px;height:64px;font-size:1.8rem">
                    <i class="bi bi-person-fill"></i>
                </div>
                <h5 class="fw-bold mb-0"><?= htmlspecialchars($cliente->getNombre() . ' ' . $cliente->getApellidos()) ?></h5>
                <span class="text-muted small"><?= htmlspecialchars($cliente->getUsuario()) ?></span>
            </div>
            <hr>
            <div class="small">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Email</span>
                    <span class="fw-bold"><?= htmlspecialchars($cliente->getEmail()) ?></span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Rol</span>
                    <span class="estado-badge <?= $cliente->getRol() === 'admin' ? '' : 'badge-activa' ?>"
                          style="<?= $cliente->getRol() === 'admin' ? 'background:#ede9fe;color:#7c3aed' : '' ?>">
                        <?= ucfirst($cliente->getRol()) ?>
                    </span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Registro</span>
                    <span><?= $cliente->getFechaRegistro() ? date('d/m/Y', strtotime($cliente->getFechaRegistro())) : 'N/A' ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Resumen de reservas -->
    <div class="col-md-8">
        <div class="row g-3">
            <?php
            $totalReservas  = count($reservas);
            $reservasActivas = array_filter($reservas, fn($r) => $r->getEstado() === 'Activa');
            $totalSanciones  = array_sum(array_map(fn($r) => $r->getSancionKm() + $r->getSancionTiempo(), $reservas));
            $totalGastado    = array_sum(array_map(fn($r) => $r->getPrecioTotal(), $reservas));
            ?>
            <div class="col-6">
                <div class="stat-card">
                    <div class="stat-icon blue"><i class="bi bi-calendar-check-fill"></i></div>
                    <div><div class="stat-value"><?= $totalReservas ?></div><div class="stat-label">Reservas totales</div></div>
                </div>
            </div>
            <div class="col-6">
                <div class="stat-card">
                    <div class="stat-icon green"><i class="bi bi-check-circle-fill"></i></div>
                    <div><div class="stat-value"><?= count($reservasActivas) ?></div><div class="stat-label">Activas ahora</div></div>
                </div>
            </div>
            <div class="col-6">
                <div class="stat-card">
                    <div class="stat-icon orange"><i class="bi bi-cash-stack"></i></div>
                    <div><div class="stat-value"><?= number_format($totalGastado, 0) ?>€</div><div class="stat-label">Total gastado</div></div>
                </div>
            </div>
            <div class="col-6">
                <div class="stat-card">
                    <div class="stat-icon purple"><i class="bi bi-exclamation-triangle-fill"></i></div>
                    <div><div class="stat-value"><?= number_format($totalSanciones, 0) ?>€</div><div class="stat-label">Total sanciones</div></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tabla de reservas con sanciones -->
<div class="admin-table-card">
    <div class="table-card-header">
        <h5><i class="bi bi-calendar-week me-2"></i>Historial de reservas</h5>
    </div>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Vehículo</th>
                    <th>Recogida</th>
                    <th>Devolución</th>
                    <th>Base</th>
                    <th>Sanción km</th>
                    <th>Sanción tiempo</th>
                    <th>Total final</th>
                    <th>Estado</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($reservas)): ?>
                    <tr><td colspan="10" class="text-center text-muted py-4">Este cliente no tiene reservas.</td></tr>
                <?php else: ?>
                    <?php foreach ($reservas as $r): ?>
                        <?php $v = $r->getVehiculo(); ?>
                        <tr>
                            <td class="text-muted fw-bold">#<?= $r->getId() ?></td>
                            <td><?= $v ? htmlspecialchars($v->getNombreCompleto()) : 'N/A' ?></td>
                            <td><?= date('d/m/Y', strtotime($r->getFechaInicio())) ?></td>
                            <td><?= date('d/m/Y', strtotime($r->getFechaFin())) ?></td>
                            <td><?= $r->getPrecioTotal() ?>€</td>
                            <td><?= number_format($r->getSancionKm(), 2) ?>€</td>
                            <td><?= number_format($r->getSancionTiempo(), 2) ?>€</td>
                            <td class="fw-bold text-danger">
                                <?= number_format($r->getPrecioTotal() + $r->getSancionKm() + $r->getSancionTiempo(), 2) ?>€
                            </td>
                            <td>
                                <span class="estado-badge badge-<?= strtolower($r->getEstado()) ?>"><?= $r->getEstado() ?></span>
                            </td>
                            <td>
                                <!-- Botón colapsar formulario de sanción -->
                                <button class="btn btn-sm btn-outline-warning"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#sancion-<?= $r->getId() ?>">
                                    <i class="bi bi-exclamation-triangle"></i>
                                </button>
                            </td>
                        </tr>
                        <!-- Fila expandible con formulario de sanción -->
                        <tr class="collapse" id="sancion-<?= $r->getId() ?>">
                            <td colspan="10" class="bg-light">
                                <form method="POST" action="/.Dual/Proyecto_Final.V2/Controller/admin/cliente_detalle.php?id=<?= $cliente->getId() ?>"
                                      class="d-flex align-items-end gap-3 p-2">
                                    <input type="hidden" name="id_reserva" value="<?= $r->getId() ?>">
                                    <div>
                                        <label class="form-label small fw-bold mb-1">Sanción por km (€)</label>
                                        <input type="number" name="sancion_km" step="0.01" min="0"
                                               class="form-control form-control-sm"
                                               value="<?= $r->getSancionKm() ?>" style="width:130px">
                                    </div>
                                    <div>
                                        <label class="form-label small fw-bold mb-1">Sanción por tiempo (€)</label>
                                        <input type="number" name="sancion_tiempo" step="0.01" min="0"
                                               class="form-control form-control-sm"
                                               value="<?= $r->getSancionTiempo() ?>" style="width:130px">
                                    </div>
                                    <button type="submit" class="btn btn-warning btn-sm">
                                        <i class="bi bi-save me-1"></i>Guardar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'layout_bottom.php'; ?>
