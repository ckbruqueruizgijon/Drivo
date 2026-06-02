<?php
require_once '../Model/Reserva.php';

// --- LÓGICA DE ACTUALIZACIÓN ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_reserva'])) {
    $id_reserva = $_POST['id_reserva'];
    $nueva_f_inicio = $_POST['nueva_fecha_inicio'];
    $nueva_f_fin = $_POST['nueva_fecha_fin'];
    $precio_final_recibido = $_POST['precio_total_final'];

    if (Reserva::actualizarFechasYPrecio($id_reserva, $nueva_f_inicio, $nueva_f_fin, $precio_final_recibido)) {
        header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . $id_reserva . "&success=1");
        exit();
    }
}

$mensaje_exito = isset($_GET['success']) ? "Reserva actualizada con éxito" : null;

// Control seguro del estado en minúsculas para el bloqueo
$estadoReserva = strtolower(trim($reserva_detalle->getEstado()));
$esFinalizada = ($estadoReserva === 'finalizada');
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Drivo | Detalle de Reserva</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../View/css/style.css">
    <link rel="stylesheet" href="../View/css/detalles.css">
    <link rel="shortcut icon" href="../View/img/logo.png" type="image/x-icon">
</head>
<body>
    <?php include '../View/header.php' ?>

    <main class="main__container detalle-container">
        <div class="container">
            <a href="../Controller/reservas.php" class="btn btn-link text-decoration-none text-primary-drivo mb-4 ps-0">
                <i class="bi bi-arrow-left"></i> Volver a mis reservas
            </a>

            <?php if ($mensaje_exito): ?>
                <div class="alert alert-success rounded-custom border-0 shadow-sm mb-4">
                    <i class="bi bi-check-circle-fill me-2"></i> <?= $mensaje_exito ?>
                </div>
            <?php endif; ?>

            <?php if ($esFinalizada): ?>
                <div class="alert alert-secondary rounded-custom border-0 shadow-sm mb-4">
                    <i class="bi bi-info-circle-fill me-2"></i> Esta reserva ya ha sido finalizada. Los datos se muestran en modo de solo lectura.
                </div>
            <?php endif; ?>

            <div class="card card-detalle shadow-lg">
                <div class="reserva-header d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div>
                        <h2 class="mb-1 fw-bold">Reserva DRV<?= str_pad($reserva_detalle->getId(), 6, "0", STR_PAD_LEFT) ?></h2>
                        <p class="mb-0 opacity-75">
                            <?= $esFinalizada ? 'Resumen e historial de los días de tu alquiler' : 'Gestiona y actualiza los días de tu alquiler' ?>
                        </p>
                    </div>
                    <span class="badge fs-6" style="background:#7BD5AB; color:#152D51; padding: 10px 20px; border-radius: 50px;">
                        <?= $reserva_detalle->getEstado() ?>
                    </span>
                </div>

                <div class="p-4 p-md-5">
                    <form id="form-update-reserva" action="" method="POST">
                        <input type="hidden" name="id_reserva" value="<?= $reserva_detalle->getId() ?>">
                        <input type="hidden" id="precio_dia_coche" value="<?= $vehiculo->getPrecioDia() ?>">
                        
                        <div class="row g-5">
                            <div class="col-lg-5 text-center border-end-lg">
                                <div class="p-3">
                                    <img src="../View/img/coches/<?= pathinfo($vehiculo->getImagen(), PATHINFO_FILENAME) ?>--sin_fondo.png" alt="Coche" class="img-fluid img-detalle mb-4">
                                    <h3 class="fw-bold text-primary-drivo mb-2"><?= $vehiculo->getNombreCompleto() ?></h3>
                                    <p class="text-muted"><i class="bi bi-tag-fill me-1"></i> Tarifa: <strong><?= $vehiculo->getPrecioDia() ?>€</strong> / día</p>
                                </div>
                            </div>

                            <div class="col-lg-7">
                                <div class="row g-4">
                                    <?php $recogidaFutura = $reserva_detalle->getFechaInicio() < date('Y-m-d'); ?>
                                    <div class="col-sm-6">
                                        <label class="info-label mb-2 d-block">
                                            Fecha de Recogida
                                            <?php if ($recogidaFutura || $esFinalizada): ?>
                                                <i class="bi bi-lock-fill ms-1 text-muted" title="No se puede modificar"></i>
                                            <?php endif; ?>
                                        </label>
                                        <input type="date" id="edit_fecha_inicio" 
                                               class="form-control rounded-custom border-2" 
                                               value="<?= $reserva_detalle->getFechaInicio() ?>" 
                                               min="<?= date('Y-m-d') ?>"
                                               <?= ($recogidaFutura || $esFinalizada) ? 'disabled' : 'name="nueva_fecha_inicio"' ?>>
                                        <?php if ($recogidaFutura && !$esFinalizada): ?>
                                            <input type="hidden" name="nueva_fecha_inicio" value="<?= $reserva_detalle->getFechaInicio() ?>">
                                            <small class="text-muted mt-1 d-block"><i class="bi bi-info-circle me-1"></i>Solo puedes modificar la fecha de devolución.</small>
                                        <?php endif; ?>
                                    </div>

                                    <div class="col-sm-6">
                                        <label class="info-label mb-2 d-block">
                                            Fecha de Devolución
                                            <?php if ($esFinalizada): ?>
                                                <i class="bi bi-lock-fill ms-1 text-muted" title="No se puede modificar"></i>
                                            <?php endif; ?>
                                        </label>
                                        <input type="date" id="edit_fecha_fin" 
                                               class="form-control rounded-custom border-2" 
                                               value="<?= $reserva_detalle->getFechaFin() ?>"
                                               min="<?= $reserva_detalle->getFechaInicio() ?>"
                                               <?= $esFinalizada ? 'disabled' : 'name="nueva_fecha_fin"' ?>>
                                    </div>

                                    <div class="col-12 mt-4">
                                        <div class="p-4 rounded-custom" style="background-color: #f8f9fa; border: 1px dashed #ced4da;">
                                            <h5 class="fw-bold text-primary-drivo mb-3 small text-uppercase">Desglose del pago</h5>
                                            
                                            <div class="d-flex justify-content-between mb-2">
                                                <span class="text-muted">Alquiler (<span id="txt-num-dias">1</span> días)</span>
                                                <span id="txt-subtotal-dias" class="fw-bold text-primary-drivo">0.00€</span>
                                            </div>

                                            <div id="fila-recargo" class="d-flex justify-content-between text-danger mb-2 d-none">
                                                <span><i class="bi bi-clock-history me-1"></i> Recargo falta de aviso (20%)</span>
                                                <span id="txt-precio-recargo" class="fw-bold">0.00€</span>
                                            </div>

                                            <hr class="my-3">

                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="fs-5 fw-bold text-primary-drivo">PRECIO TOTAL</span>
                                                <span id="precio-total-display" class="fs-2 fw-bold text-success">
                                                    <?= number_format($reserva_detalle->getPrecioTotal(), 2) ?>€
                                                </span>
                                            </div>
                                            <input type="hidden" name="precio_total_final" id="input-precio-total" value="<?= $reserva_detalle->getPrecioTotal() ?>">
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <?php if (!$esFinalizada): ?>
                                            <button type="submit" class="btn-full py-3 shadow-sm">
                                                ACTUALIZAR MI RESERVA
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <?php include '../View/footer.php' ?>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const inputInicio = document.getElementById('edit_fecha_inicio');
        const inputFin = document.getElementById('edit_fecha_fin');
        const precioDia = parseFloat(document.getElementById('precio_dia_coche').value);

        // Fechas originales de la reserva (para detectar si el usuario las cambió)
        const fechaInicioOriginal = inputInicio.value;
        const fechaFinOriginal = inputFin.value;
        
        const txtNumDias = document.getElementById('txt-num-dias');
        const txtSubtotalDias = document.getElementById('txt-subtotal-dias');
        const filaRecargo = document.getElementById('fila-recargo');
        const txtPrecioRecargo = document.getElementById('txt-precio-recargo');
        const displayTotal = document.getElementById('precio-total-display');
        const inputTotalHidden = document.getElementById('input-precio-total');

        function actualizarCalculos() {
            const f1 = new Date(inputInicio.value);
            const f2 = new Date(inputFin.value);
            const hoy = new Date();
            
            hoy.setHours(0, 0, 0, 0);
            f1.setHours(0, 0, 0, 0);

            if (f1 && f2 && f2 >= f1) {
                const diffTime = Math.abs(f2 - f1);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
                let subtotalBase = diffDays * precioDia;

                // Solo se aplica el recargo si las fechas han cambiado respecto a las originales
                const fechasCambiadas = (inputInicio.value !== fechaInicioOriginal || inputFin.value !== fechaFinOriginal);

                // Los días faltantes se miden desde HOY hasta la fecha ORIGINAL de la reserva
                const fOriginal = new Date(fechaInicioOriginal);
                fOriginal.setHours(0, 0, 0, 0);
                const msDiferencia = fOriginal.getTime() - hoy.getTime();
                const diasFaltantes = msDiferencia / (1000 * 60 * 60 * 24);

                let montoRecargo = 0;
                if (fechasCambiadas && diasFaltantes <= 1) {
                    montoRecargo = subtotalBase * 0.20;
                    filaRecargo.classList.remove('d-none');
                } else {
                    montoRecargo = 0;
                    filaRecargo.classList.add('d-none');
                }

                const totalFinal = subtotalBase + montoRecargo;
                
                txtNumDias.innerText = diffDays;
                txtSubtotalDias.innerText = subtotalBase.toFixed(2) + '€';
                txtPrecioRecargo.innerText = montoRecargo.toFixed(2) + '€';
                displayTotal.innerText = totalFinal.toFixed(2) + '€';
                inputTotalHidden.value = totalFinal.toFixed(2);
            }
        }

        if (inputInicio && inputFin) {
            inputInicio.addEventListener('change', function() {
                inputFin.min = inputInicio.value;
                actualizarCalculos();
            });
            inputFin.addEventListener('change', actualizarCalculos);
            actualizarCalculos();
        }
    });
    </script>
</body>
</html>