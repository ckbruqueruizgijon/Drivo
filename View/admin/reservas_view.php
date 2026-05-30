<?php
$tituloPagina = 'Gestión de Reservas';
include 'layout_top.php';
?>

<p class="page-title">Todas las Reservas</p>
<p class="page-subtitle">Consulta, filtra y modifica el estado de cualquier reserva del sistema.</p>

<!-- BARRA DE FILTROS -->
<div class="filter-bar mb-3">
    <div class="d-flex gap-3 align-items-end flex-wrap">
        <div style="flex:1; min-width:200px">
            <label class="filter-label"><i class="bi bi-search me-1"></i>Buscar cliente o vehículo</label>
            <div class="input-group">
                <span class="input-group-text bg-white"><i class="bi bi-search text-muted" style="font-size:.85rem"></i></span>
                <input type="text" id="f-res-texto" class="form-control"
                       placeholder="Nombre, email, marca..."
                       oninput="filtrarReservas()">
            </div>
        </div>
        <div>
            <label class="filter-label"><i class="bi bi-flag-fill me-1"></i>Estado</label>
            <select id="f-res-estado" class="form-select" style="min-width:140px" onchange="filtrarReservas()">
                <option value="">Todos</option>
                <option value="Pendiente">Pendiente</option>
                <option value="Activa">Activa</option>
                <option value="Finalizada">Finalizada</option>
                <option value="Cancelada">Cancelada</option>
            </select>
        </div>
        <div>
            <label class="filter-label"><i class="bi bi-calendar-event me-1"></i>Fecha desde</label>
            <input type="date" id="f-res-fecha-ini" class="form-control" style="width:155px" onchange="filtrarReservas()">
        </div>
        <div>
            <label class="filter-label"><i class="bi bi-calendar-event me-1"></i>Fecha hasta</label>
            <input type="date" id="f-res-fecha-fin" class="form-control" style="width:155px" onchange="filtrarReservas()">
        </div>
        <div>
            <label class="filter-label">&nbsp;</label>
            <button onclick="limpiarReservas()" class="btn btn-outline-secondary d-flex align-items-center gap-1">
                <i class="bi bi-x-circle"></i> Limpiar
            </button>
        </div>
    </div>
    <p class="filter-count mb-0 mt-2">
        Mostrando <span id="count-reservas"><?= count($reservas) ?></span> de <?= count($reservas) ?> reservas
    </p>
</div>

<div class="admin-table-card">
    <div class="table-card-header">
        <h5><i class="bi bi-calendar-check-fill me-2"></i>Reservas totales (<?= count($reservas) ?>)</h5>
    </div>
    <div class="table-responsive">
        <table class="table table-hover" id="tabla-reservas">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Cliente</th>
                    <th>Vehículo</th>
                    <th>Fecha reserva</th>
                    <th>Recogida</th>
                    <th>Devolución</th>
                    <th>Total</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($reservas)): ?>
                    <tr><td colspan="8" class="text-center text-muted py-4">No hay reservas en el sistema.</td></tr>
                <?php else: ?>
                    <?php foreach ($reservas as $r): ?>
                        <tr data-estado="<?= $r->estado ?>"
                            data-fecha-ini="<?= $r->fecha_inicio ?>"
                            data-fecha-fin="<?= $r->fecha_fin ?>">
                            <td class="text-muted fw-bold">#<?= $r->id ?></td>
                            <td>
                                <div class="fw-bold"><?= htmlspecialchars($r->nombre . ' ' . $r->apellidos) ?></div>
                                <div class="text-muted small"><?= htmlspecialchars($r->email) ?></div>
                            </td>
                            <td><?= htmlspecialchars($r->marca . ' ' . $r->modelo) ?></td>
                            <td class="text-muted small"><?= date('d/m/Y H:i', strtotime($r->fecha_reserva)) ?></td>
                            <td><?= date('d/m/Y', strtotime($r->fecha_inicio)) ?></td>
                            <td><?= date('d/m/Y', strtotime($r->fecha_fin)) ?></td>
                            <td class="fw-bold"><?= $r->precio_total ?>€</td>
                            <td>
                                <form method="POST" action="/.Dual/Proyecto_Final.V2/Controller/admin/reservas_admin.php">
                                    <input type="hidden" name="id_reserva" value="<?= $r->id ?>">
                                    <select name="estado" class="select-estado" onchange="this.form.submit()">
                                        <?php foreach (['Pendiente','Activa','Finalizada','Cancelada'] as $est): ?>
                                            <option value="<?= $est ?>" <?= $r->estado === $est ? 'selected' : '' ?>><?= $est ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function filtrarReservas() {
    const texto    = document.getElementById('f-res-texto').value.toLowerCase().trim();
    const estado   = document.getElementById('f-res-estado').value;
    const fechaIni = document.getElementById('f-res-fecha-ini').value; // 'YYYY-MM-DD'
    const fechaFin = document.getElementById('f-res-fecha-fin').value;
    const filas    = document.querySelectorAll('#tabla-reservas tbody tr');
    let visibles = 0;

    filas.forEach(fila => {
        // Columnas: 0=#, 1=cliente, 2=vehiculo, 3=fecha reserva, 4=recogida, 5=devolucion
        const cliente  = (fila.cells[1]?.textContent || '').toLowerCase();
        const vehiculo = (fila.cells[2]?.textContent || '').toLowerCase();

        const filaDatIni = fila.dataset.fechaIni || ''; // 'YYYY-MM-DD HH:MM:SS'
        const filaDatFin = fila.dataset.fechaFin || '';

        const matchTexto  = !texto  || cliente.includes(texto) || vehiculo.includes(texto);
        const matchEstado = !estado || fila.dataset.estado === estado;
        // Comparar: la recogida de la reserva debe ser >= fecha filtro inicio
        const matchFini   = !fechaIni || filaDatIni.substring(0,10) >= fechaIni;
        // La devolución de la reserva debe ser <= fecha filtro fin
        const matchFfin   = !fechaFin || filaDatFin.substring(0,10) <= fechaFin;

        const mostrar = matchTexto && matchEstado && matchFini && matchFfin;
        fila.style.display = mostrar ? '' : 'none';
        if (mostrar) visibles++;
    });

    document.getElementById('count-reservas').textContent = visibles;
}

function limpiarReservas() {
    document.getElementById('f-res-texto').value     = '';
    document.getElementById('f-res-estado').value    = '';
    document.getElementById('f-res-fecha-ini').value = '';
    document.getElementById('f-res-fecha-fin').value = '';
    filtrarReservas();
}
</script>

<?php include 'layout_bottom.php'; ?>
