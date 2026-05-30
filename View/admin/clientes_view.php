<?php
$tituloPagina = 'Gestión de Clientes';
include 'layout_top.php';
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <p class="page-title mb-0">Clientes Registrados</p>
        <p class="page-subtitle mb-0">Listado de todos los usuarios del sistema.</p>
    </div>
</div>

<!-- BARRA DE FILTROS -->
<div class="filter-bar mb-3">
    <div class="d-flex gap-3 align-items-end flex-wrap">
        <div style="flex:1; min-width:220px">
            <label class="filter-label"><i class="bi bi-search me-1"></i>Buscar</label>
            <div class="input-group">
                <span class="input-group-text bg-white"><i class="bi bi-search text-muted" style="font-size:.85rem"></i></span>
                <input type="text" id="f-texto" class="form-control"
                       placeholder="Nombre, email, usuario..."
                       oninput="filtrarClientes()">
            </div>
        </div>
        <div>
            <label class="filter-label"><i class="bi bi-shield-fill me-1"></i>Rol</label>
            <select id="f-rol" class="form-select" style="min-width:130px" onchange="filtrarClientes()">
                <option value="">Todos</option>
                <option value="admin">Admin</option>
                <option value="cliente">Cliente</option>
            </select>
        </div>
        <div>
            <label class="filter-label">&nbsp;</label>
            <button onclick="limpiarClientes()" class="btn btn-outline-secondary d-flex align-items-center gap-1">
                <i class="bi bi-x-circle"></i> Limpiar
            </button>
        </div>
    </div>
    <p class="filter-count mb-0 mt-2">
        Mostrando <span id="count-clientes"><?= count($clientes) ?></span> de <?= count($clientes) ?> clientes
    </p>
</div>

<div class="admin-table-card">
    <div class="table-card-header">
        <h5><i class="bi bi-people-fill me-2"></i>Total de usuarios (<?= count($clientes) ?>)</h5>
    </div>
    <div class="table-responsive">
        <table class="table table-hover" id="tabla-clientes">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Usuario</th>
                    <th>Nombre completo</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Registro</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($clientes)): ?>
                    <tr><td colspan="7" class="text-center text-muted py-4">No hay clientes registrados.</td></tr>
                <?php else: ?>
                    <?php foreach ($clientes as $c): ?>
                        <tr>
                            <td class="text-muted fw-bold"><?= $c->getId() ?></td>
                            <td><code><?= htmlspecialchars($c->getUsuario()) ?></code></td>
                            <td class="fw-bold"><?= htmlspecialchars($c->getNombre() . ' ' . $c->getApellidos()) ?></td>
                            <td><?= htmlspecialchars($c->getEmail()) ?></td>
                            <td>
                                <?php if ($c->getRol() === 'admin'): ?>
                                    <span class="estado-badge" style="background:#ede9fe;color:#7c3aed">
                                        <i class="bi bi-shield-fill-check me-1"></i>Admin
                                    </span>
                                <?php else: ?>
                                    <span class="estado-badge badge-activa">
                                        <i class="bi bi-person-fill me-1"></i>Cliente
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="text-muted small">
                                <?= $c->getFechaRegistro() ? date('d/m/Y', strtotime($c->getFechaRegistro())) : 'N/A' ?>
                            </td>
                            <td>
                                <a href="/.Dual/Proyecto_Final.V2/Controller/admin/cliente_detalle.php?id=<?= $c->getId() ?>"
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye-fill me-1"></i>Ver detalle
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function filtrarClientes() {
    const texto = document.getElementById('f-texto').value.toLowerCase().trim();
    const rol   = document.getElementById('f-rol').value.toLowerCase().trim();
    const filas = document.querySelectorAll('#tabla-clientes tbody tr');
    let visibles = 0;

    filas.forEach(fila => {
        // Columnas: 0=ID, 1=usuario, 2=nombre, 3=email, 4=rol, 5=fecha, 6=acción
        const usuario = (fila.cells[1]?.textContent || '').toLowerCase();
        const nombre  = (fila.cells[2]?.textContent || '').toLowerCase();
        const email   = (fila.cells[3]?.textContent || '').toLowerCase();
        const rolCell = (fila.cells[4]?.textContent || '').toLowerCase().trim();

        const matchTexto = !texto || usuario.includes(texto) || nombre.includes(texto) || email.includes(texto);
        const matchRol   = !rol   || rolCell.includes(rol);

        const mostrar = matchTexto && matchRol;
        fila.style.display = mostrar ? '' : 'none';
        if (mostrar) visibles++;
    });

    document.getElementById('count-clientes').textContent = visibles;
}

function limpiarClientes() {
    document.getElementById('f-texto').value = '';
    document.getElementById('f-rol').value   = '';
    filtrarClientes();
}
</script>

<?php include 'layout_bottom.php'; ?>
