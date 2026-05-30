<?php
$esEdicion = $vehiculoEditar !== null;
$tituloPagina = $esEdicion ? 'Editar Vehículo' : 'Añadir Vehículo';
include 'layout_top.php';
?>

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb" style="font-size:0.85rem">
        <li class="breadcrumb-item"><a href="/.Dual/Proyecto_Final.V2/Controller/admin/vehiculos.php">Vehículos</a></li>
        <li class="breadcrumb-item active"><?= $esEdicion ? 'Editar' : 'Añadir nuevo' ?></li>
    </ol>
</nav>

<p class="page-title"><?= $esEdicion ? 'Editar: ' . htmlspecialchars($vehiculoEditar->getNombreCompleto()) : 'Añadir Vehículo' ?></p>
<p class="page-subtitle"><?= $esEdicion ? 'Modifica los datos del vehículo.' : 'Rellena el formulario para añadir un coche a la flota.' ?></p>

<div class="admin-table-card p-4" style="max-width:800px">
    <form method="POST" action="/.Dual/Proyecto_Final.V2/Controller/admin/vehiculo_form.php" enctype="multipart/form-data">

        <?php if ($esEdicion): ?>
            <input type="hidden" name="id" value="<?= $vehiculoEditar->getId() ?>">
            <input type="hidden" name="imagen_actual" value="<?= htmlspecialchars($vehiculoEditar->getImagen()) ?>">
        <?php endif; ?>

        <div class="row g-3">
            <!-- Matrícula -->
            <div class="col-md-4">
                <label class="form-label fw-bold">Matrícula *</label>
                <input type="text" name="matricula" class="form-control" required
                       placeholder="0000-AAA"
                       value="<?= $esEdicion ? htmlspecialchars($vehiculoEditar->getMatricula()) : '' ?>">
            </div>
            <!-- Marca -->
            <div class="col-md-4">
                <label class="form-label fw-bold">Marca *</label>
                <input type="text" name="marca" class="form-control" required
                       placeholder="Ej. Volkswagen"
                       value="<?= $esEdicion ? htmlspecialchars($vehiculoEditar->getMarca()) : '' ?>">
            </div>
            <!-- Modelo -->
            <div class="col-md-4">
                <label class="form-label fw-bold">Modelo *</label>
                <input type="text" name="modelo" class="form-control" required
                       placeholder="Ej. Golf"
                       value="<?= $esEdicion ? htmlspecialchars($vehiculoEditar->getModelo()) : '' ?>">
            </div>
            <!-- Motor -->
            <div class="col-md-6">
                <label class="form-label fw-bold">Motor *</label>
                <input type="text" name="motor" class="form-control" required
                       placeholder="Ej. Gasolina 2.0 TFSI 197cv"
                       value="<?= $esEdicion ? htmlspecialchars($vehiculoEditar->getMotor()) : '' ?>">
            </div>
            <!-- Cambios -->
            <div class="col-md-3">
                <label class="form-label fw-bold">Cambios *</label>
                <select name="cambios" class="form-select" required>
                    <?php foreach (['Automática','Manual 6v','Manual 5v'] as $opt): ?>
                        <option value="<?= $opt ?>" <?= ($esEdicion && $vehiculoEditar->getCambios() === $opt) ? 'selected' : '' ?>>
                            <?= $opt ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <!-- Tracción -->
            <div class="col-md-3">
                <label class="form-label fw-bold">Tracción *</label>
                <select name="traccion" class="form-select" required>
                    <?php foreach (['Delantera','Trasera','a las 4 ruedas'] as $opt): ?>
                        <option value="<?= $opt ?>" <?= ($esEdicion && $vehiculoEditar->getTraccion() === $opt) ? 'selected' : '' ?>>
                            <?= $opt ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <!-- Llantas -->
            <div class="col-md-2">
                <label class="form-label fw-bold">Llantas (pulgadas)</label>
                <input type="number" name="llantas" class="form-control" min="14" max="24"
                       value="<?= $esEdicion ? $vehiculoEditar->getLlantas() : 17 ?>">
            </div>
            <!-- Año -->
            <div class="col-md-2">
                <label class="form-label fw-bold">Año *</label>
                <input type="number" name="anio" class="form-control" required
                       min="2000" max="<?= date('Y') + 1 ?>"
                       value="<?= $esEdicion ? $vehiculoEditar->getAnio() : date('Y') ?>">
            </div>
            <!-- Precio/día -->
            <div class="col-md-3">
                <label class="form-label fw-bold">Precio por día (€) *</label>
                <input type="number" name="precio_dia" class="form-control" required
                       step="0.01" min="0"
                       value="<?= $esEdicion ? $vehiculoEditar->getPrecioDia() : '' ?>">
            </div>

            <!-- Imagen -->
            <div class="col-md-5">
                <label class="form-label fw-bold">Imagen <?= $esEdicion ? '(dejar vacío para mantener)' : '*' ?></label>
                <input type="file" name="imagen" class="form-control"
                       accept="image/jpeg,image/png,image/webp,image/avif"
                       <?= $esEdicion ? '' : 'required' ?>>
                <?php if ($esEdicion): ?>
                    <div class="form-text">Actual: <code><?= htmlspecialchars($vehiculoEditar->getImagen()) ?></code></div>
                <?php endif; ?>
            </div>

            <!-- Checkboxes -->
            <div class="col-12 d-flex gap-4 mt-2">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="disponible" id="chk_disponible"
                           <?= (!$esEdicion || $vehiculoEditar->getDisponible()) ? 'checked' : '' ?>>
                    <label class="form-check-label fw-bold" for="chk_disponible">Disponible</label>
                </div>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="oferta" id="chk_oferta"
                           <?= ($esEdicion && $vehiculoEditar->getOferta()) ? 'checked' : '' ?>>
                    <label class="form-check-label fw-bold" for="chk_oferta">En oferta</label>
                </div>
            </div>

            <!-- Botones -->
            <div class="col-12 d-flex gap-2 mt-3 pt-3 border-top">
                <button type="submit" class="btn btn-primary px-4" style="background:#152D51;border-color:#152D51">
                    <i class="bi bi-save me-2"></i><?= $esEdicion ? 'Guardar cambios' : 'Añadir vehículo' ?>
                </button>
                <a href="/.Dual/Proyecto_Final.V2/Controller/admin/vehiculos.php" class="btn btn-outline-secondary">
                    Cancelar
                </a>
            </div>
        </div>
    </form>
</div>

<?php include 'layout_bottom.php'; ?>
