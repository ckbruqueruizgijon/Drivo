<?php
$tituloPagina = 'Gestión de Vehículos';
include 'layout_top.php';

$mensajes = [
    'creado'       => ['success', 'Vehículo añadido correctamente.'],
    'editado'      => ['success', 'Vehículo actualizado correctamente.'],
    'eliminado'    => ['success', 'Vehículo eliminado de la flota.'],
    'error'        => ['danger',  'Ha ocurrido un error. Comprueba los datos.'],
    'error_delete' => ['danger',  'No se puede eliminar: el vehículo tiene reservas asociadas.'],
];
$ok = $_GET['ok'] ?? null;
?>

<?php if ($ok && isset($mensajes[$ok])): ?>
    <div class="alert alert-<?= $mensajes[$ok][0] ?> alert-dismissible fade show mb-3" role="alert">
        <i class="bi bi-<?= $mensajes[$ok][0] === 'success' ? 'check-circle-fill' : 'exclamation-triangle-fill' ?> me-2"></i>
        <?= $mensajes[$ok][1] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <p class="page-title mb-0">Flota de Vehículos</p>
        <p class="page-subtitle mb-0">Gestiona disponibilidad, ofertas y el catálogo completo.</p>
    </div>
    <a href="/.Dual/Proyecto_Final.V2/Controller/admin/vehiculo_form.php" class="btn btn-primary px-4"
       style="background:#152D51;border-color:#152D51">
        <i class="bi bi-plus-lg me-2"></i>Añadir vehículo
    </a>
</div>

<!-- BARRA DE FILTROS -->
<div class="filter-bar mb-3">
    <div class="d-flex gap-3 align-items-end flex-wrap">
        <div style="flex:1; min-width:200px">
            <label class="filter-label"><i class="bi bi-search me-1"></i>Buscar</label>
            <div class="input-group">
                <span class="input-group-text bg-white"><i class="bi bi-search text-muted" style="font-size:.85rem"></i></span>
                <input type="text" id="f-veh-texto" class="form-control"
                       placeholder="Marca, modelo, matrícula..."
                       oninput="filtrarVehiculos()">
            </div>
        </div>
        <div>
            <label class="filter-label"><i class="bi bi-calendar-date me-1"></i>Año desde</label>
            <input type="number" id="f-veh-anio" class="form-control" placeholder="Ej. 2020"
                   min="2000" max="2030" style="width:110px" oninput="filtrarVehiculos()">
        </div>
        <div>
            <label class="filter-label"><i class="bi bi-circle-fill me-1"></i>Disponible</label>
            <select id="f-veh-disponible" class="form-select" style="min-width:120px" onchange="filtrarVehiculos()">
                <option value="">Todos</option>
                <option value="si">Sí</option>
                <option value="no">No</option>
            </select>
        </div>
        <div>
            <label class="filter-label"><i class="bi bi-tag-fill me-1"></i>Oferta</label>
            <select id="f-veh-oferta" class="form-select" style="min-width:120px" onchange="filtrarVehiculos()">
                <option value="">Todas</option>
                <option value="si">Sí</option>
                <option value="no">No</option>
            </select>
        </div>
        <div>
            <label class="filter-label">&nbsp;</label>
            <button onclick="limpiarVehiculos()" class="btn btn-outline-secondary d-flex align-items-center gap-1">
                <i class="bi bi-x-circle"></i> Limpiar
            </button>
        </div>
    </div>
    <p class="filter-count mb-0 mt-2">
        Mostrando <span id="count-vehiculos"><?= count($coches) ?></span> de <?= count($coches) ?> vehículos
    </p>
</div>

<div class="admin-table-card">
    <div class="table-card-header">
        <h5><i class="bi bi-car-front-fill me-2"></i>Todos los vehículos (<?= count($coches) ?>)</h5>
    </div>
    <div class="table-responsive">
        <table class="table table-hover" id="tabla-vehiculos">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Matrícula</th>
                    <th>Vehículo</th>
                    <th>Motor</th>
                    <th>Año</th>
                    <th>€/día</th>
                    <th>Disponible</th>
                    <th>Oferta</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($coches as $coche): ?>
                    <tr data-disponible="<?= $coche->getDisponible() ? 'si' : 'no' ?>"
                        data-oferta="<?= $coche->getOferta() ? 'si' : 'no' ?>"
                        data-anio="<?= $coche->getAnio() ?>">
                        <td class="text-muted fw-bold"><?= $coche->getId() ?></td>
                        <td><code><?= htmlspecialchars($coche->getMatricula()) ?></code></td>
                        <td class="fw-bold"><?= htmlspecialchars($coche->getNombreCompleto()) ?></td>
                        <td class="text-muted small"><?= htmlspecialchars($coche->getMotor()) ?></td>
                        <td><?= $coche->getAnio() ?></td>
                        <td class="fw-bold"><?= $coche->getPrecioDia() ?>€</td>

                        <td>
                            <form method="POST" action="/.Dual/Proyecto_Final.V2/Controller/admin/vehiculos.php" style="display:inline">
                                <input type="hidden" name="action" value="toggle_disponible">
                                <input type="hidden" name="id" value="<?= $coche->getId() ?>">
                                <button type="submit" class="btn-toggle <?= $coche->getDisponible() ? 'on' : 'off' ?>">
                                    <i class="bi bi-circle-fill" style="font-size:0.5rem"></i>
                                    <?= $coche->getDisponible() ? 'Sí' : 'No' ?>
                                </button>
                            </form>
                        </td>
                        <td>
                            <form method="POST" action="/.Dual/Proyecto_Final.V2/Controller/admin/vehiculos.php" style="display:inline">
                                <input type="hidden" name="action" value="toggle_oferta">
                                <input type="hidden" name="id" value="<?= $coche->getId() ?>">
                                <button type="submit" class="btn-toggle <?= $coche->getOferta() ? 'on' : 'off' ?>">
                                    <i class="bi bi-tag-fill" style="font-size:0.7rem"></i>
                                    <?= $coche->getOferta() ? 'Sí' : 'No' ?>
                                </button>
                            </form>
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="/.Dual/Proyecto_Final.V2/Controller/admin/vehiculo_form.php?id=<?= $coche->getId() ?>"
                                   class="btn btn-sm btn-outline-primary" title="Editar">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>
                                <form method="POST" action="/.Dual/Proyecto_Final.V2/Controller/admin/vehiculos.php"
                                      onsubmit="return confirm('¿Eliminar <?= htmlspecialchars($coche->getNombreCompleto()) ?>? Esta acción no se puede deshacer.')">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?= $coche->getId() ?>">
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function filtrarVehiculos() {
    const texto      = document.getElementById('f-veh-texto').value.toLowerCase().trim();
    const anioMin    = parseInt(document.getElementById('f-veh-anio').value) || 0;
    const disponible = document.getElementById('f-veh-disponible').value;
    const oferta     = document.getElementById('f-veh-oferta').value;
    const filas      = document.querySelectorAll('#tabla-vehiculos tbody tr');
    let visibles = 0;

    filas.forEach(fila => {
        // Columnas: 0=ID, 1=matrícula, 2=vehículo, 3=motor, 4=año
        const matricula = (fila.cells[1]?.textContent || '').toLowerCase();
        const vehiculo  = (fila.cells[2]?.textContent || '').toLowerCase();
        const motor     = (fila.cells[3]?.textContent || '').toLowerCase();
        const anio      = parseInt(fila.dataset.anio) || 0;

        const matchTexto      = !texto      || matricula.includes(texto) || vehiculo.includes(texto) || motor.includes(texto);
        const matchAnio       = !anioMin    || anio >= anioMin;
        const matchDisponible = !disponible || fila.dataset.disponible === disponible;
        const matchOferta     = !oferta     || fila.dataset.oferta === oferta;

        const mostrar = matchTexto && matchAnio && matchDisponible && matchOferta;
        fila.style.display = mostrar ? '' : 'none';
        if (mostrar) visibles++;
    });

    document.getElementById('count-vehiculos').textContent = visibles;
}

function limpiarVehiculos() {
    document.getElementById('f-veh-texto').value      = '';
    document.getElementById('f-veh-anio').value       = '';
    document.getElementById('f-veh-disponible').value = '';
    document.getElementById('f-veh-oferta').value     = '';
    filtrarVehiculos();
}
</script>

<?php include 'layout_bottom.php'; ?>
