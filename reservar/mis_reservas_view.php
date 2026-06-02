<?php
// Requerimos el modelo de valoraciones para comprobar las restricciones
require_once '../Model/Valoracion.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Drivo | Mis Reservas</title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../View/css/style.css">
    <link rel="stylesheet" href="../View/css/mis_reservas.css">
    <link rel="shortcut icon" href="../View/img/logo.png" type="image/x-icon">
</head>

<body>
    <?php include '../View/header.php' ?>

    <main class="main__container reservas-container">

        <?php if (isset($_SESSION['mensaje_exito'])): ?>
            <div class="alert alert-success alert-dismissible fade show rounded-custom" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> <?= $_SESSION['mensaje_exito'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['mensaje_exito']); ?>
        <?php endif; ?>

        <h2 class="text-center text-uppercase fw-bold mb-5 title-reservas text-primary-drivo">Mis Reservas</h2>

        <div class="container-mis-reservas mx-auto">

            <?php if (!empty($activas)): ?>
                <?php foreach ($activas as $reserva):
                    $vehiculo = $reserva->getVehiculo();
                ?>
                    <div class="card-proxima-reserva mb-5">
                        <div class="row g-0 align-items-center">
                            <div class="col-md-5 text-center p-4">
                                <img src="../View/img/coches/<?= pathinfo($vehiculo->getImagen(), PATHINFO_FILENAME) ?>--sin_fondo.png" alt="<?= $vehiculo->getNombreCompleto() ?>" class="img-fluid car-img-proxima">
                            </div>
                            <div class="col-md-7 p-4 p-md-5">
                                <h4 class="text-primary-drivo mb-3 fs-5">Proxima Reserva: <?= $vehiculo->getNombreCompleto() ?> - Ref: DRV<?= str_pad($reserva->getId(), 6, "0", STR_PAD_LEFT) ?></h4>
                                <p class="mb-2 text-primary-drivo"><strong>Recogida:</strong> <?= date('d/m/Y, h A', strtotime($reserva->getFechaInicio())) ?>, Aeropuerto Sevilla</p>
                                <p class="mb-2 text-primary-drivo"><strong>Devolución:</strong> <?= date('d/m/Y, h A', strtotime($reserva->getFechaFin())) ?>, Aeropuerto Sevilla</p>
                                <?php
                                $estadoMap = [
                                    'Pendiente'  => ['label' => 'Pendiente',   'color' => '#d97706', 'bg' => '#fef3c7'],
                                    'Activa'     => ['label' => 'Confirmada',  'color' => '#065f46', 'bg' => '#d1fae5'],
                                    'Finalizada' => ['label' => 'Finalizada',  'color' => '#6b7280', 'bg' => '#e5e7eb'],
                                    'Cancelada'  => ['label' => 'Cancelada',   'color' => '#dc2626', 'bg' => '#fee2e2'],
                                ];
                                $est = $reserva->getEstado();
                                $info = $estadoMap[$est] ?? ['label' => $est, 'color' => '#666', 'bg' => '#eee'];
                                
                                // Pasamos el string a minúsculas y limpiamos espacios para evitar fallos de matching
                                $estadoLimpio = strtolower(trim($est));
                                ?>
                                <p class="mb-4 text-primary-drivo"><strong>Estado:</strong>
                                    <span style="background:<?= $info['bg'] ?>;color:<?= $info['color'] ?>;padding:3px 12px;border-radius:20px;font-size:.8rem;font-weight:600">
                                        <?= $info['label'] ?>
                                    </span>
                                </p>

                                <!-- CONTENEDOR DE BOTONES ALINEADOS -->
                                <div class="d-flex flex-wrap gap-3 align-items-center">
                                    <?php if ($estadoLimpio === 'finalizada'): ?>
                                        <!-- Si está Finalizada, quitamos la opción de Modificar -->
                                        <a href="detalles.php?id=<?= $reserva->getId() ?>" class="btn btn-outline-drivo">
                                            Ver Detalles
                                        </a>
                                        
                                        <!-- Botón calificar colocado a la derecha de Ver Detalles -->
                                        <?php if (isset($_SESSION['id_cliente']) && Valoracion::puedeValorar($_SESSION['id_cliente'], $vehiculo->getId())): ?>
                                            <button type="button" class="btn btn-warning fw-bold text-dark rounded-custom px-4" data-bs-toggle="modal" data-bs-target="#modalCalificarReservas<?= $reserva->getId() ?>">
                                                <i class="bi bi-star-fill me-1"></i> Calificar
                                            </button>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <!-- Si está pendiente o confirmada, mantiene la opción completa -->
                                        <a href="detalles.php?id=<?= $reserva->getId() ?>" class="btn btn-outline-drivo">
                                            Ver Detalles / Modificar
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- VENTANA MODAL PARA RECOGER LA CALIFICACIÓN -->
                    <div class="modal fade" id="modalCalificarReservas<?= $reserva->getId() ?>" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content rounded-custom border-0 shadow-lg">
                                <div class="modal-header text-white p-4" style="background-color: #152D51;">
                                    <h5 class="modal-title fw-bold"><i class="bi bi-chat-left-heart-fill me-2"></i>Calificar <?= $vehiculo->getNombreCompleto() ?></h5>
                                    <button type="button" class="btn-close btn-close-white shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form action="../Controller/guardar_valoracion.php" method="POST">
                                    <div class="modal-body p-4 bg-light">
                                        <input type="hidden" name="id_vehiculo" value="<?= $vehiculo->getId() ?>">
                                        
                                        <div class="mb-3">
                                            <label class="form-label fw-bold text-primary-drivo small text-uppercase">¿Qué puntuación le das?</label>
                                            <select name="puntuacion" class="form-select border-2" style="border-radius: 0.5rem;" required>
                                                <option value="5">⭐⭐⭐⭐⭐ (Excelente)</option>
                                                <option value="4">⭐⭐⭐⭐ (Muy bueno)</option>
                                                <option value="3">⭐⭐⭐ (Normal)</option>
                                                <option value="2">⭐⭐ (Regular)</option>
                                                <option value="1">⭐ (Malo)</option>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label fw-bold text-primary-drivo small text-uppercase">Cuéntanos tu experiencia</label>
                                            <textarea name="comentario" class="form-control border-2" style="border-radius: 0.5rem;" rows="4" placeholder="Escribe aquí tu comentario sobre el coche..." required></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer p-3 border-0 bg-light">
                                        <button type="button" class="btn btn-secondary rounded-custom" data-bs-dismiss="modal">Cancelar</button>
                                        <button type="submit" class="btn text-white fw-bold rounded-custom px-4" style="background-color: #152D51;">Enviar valoración</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- FIN DEL MODAL -->

                <?php endforeach; ?>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="bi bi-car-front text-muted icon-xl"></i>
                    <h4 class="mt-3 text-muted">No tienes reservas activas</h4>
                    <a href="coches.php" class="btn mt-3 btn-catalogo">Ver Catálogo</a>
                </div>
            <?php endif; ?>

            <!-- HISTORIAL DE RESERVAS ABAJO -->
            <h3 class="text-primary-drivo mb-4 fs-4 mt-5">Historial de reservas</h3>

            <?php if (!empty($anteriores)): ?>
                <div class="historial-list">
                    <?php foreach ($anteriores as $reserva):
                        $vehiculo = $reserva->getVehiculo();
                        $estadoMapH = [
                            'Pendiente'  => 'Pendiente',
                            'Activa'     => 'Activa',
                            'Finalizada' => 'Completada',
                            'Cancelada'  => 'Cancelada',
                        ];
                        $textoEstado = $estadoMapH[$reserva->getEstado()] ?? $reserva->getEstado();
                    ?>
                        <div class="card-historial mb-3 w-75">
                            <div class="row g-0 align-items-center">
                                <div class="col-4 col-sm-3 text-center p-3">
                                    <img src="../View/img/coches/<?= pathinfo($vehiculo->getImagen(), PATHINFO_FILENAME) ?>--sin_fondo.png" alt="<?= $vehiculo->getNombreCompleto() ?>" class="img-fluid car-img-historial">
                                </div>
                                <div class="col-8 col-sm-9 p-3">
                                    <p class="mb-1 text-primary-drivo fs-6"><?= $vehiculo->getNombreCompleto() ?> - Ref: DRV<?= str_pad($reserva->getId(), 6, "0", STR_PAD_LEFT) ?></p>
                                    <p class="mb-0 text-primary-drivo fs-6">(<?= $textoEstado ?>)</p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="text-muted">No tienes historial de reservas.</p>
            <?php endif; ?>

        </div>
    </main>

    <?php include '../View/footer.php' ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>