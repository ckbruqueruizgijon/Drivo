<?php
// Requerimos el modelo de valoraciones para poder usar sus métodos estáticos
require_once '../Model/Valoracion.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Drivo | Nuestros Coches</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../View/css/style.css">
    <link rel="shortcut icon" href="../View/img/logo.png" type="image/x-icon">
</head>
<body>
    <?php include '../View/header.php' ?>

    <main class="bg-white w-100 pb-5 pt-1">
        <div class="main__container">
            <h1 class="title mt-3">NUESTROS COCHES</h1>
            
            <div class="ofertas__flex">
                <?php if (!empty($coches)): ?>
                    <?php foreach ($coches as $coche): ?>
                        <div class="oferta">
                            <h3 class="modelo"><?= $coche->getNombreCompleto() ?></h3>
                            
                            <div class="photo__container">
                                <img class="photo" src="../View/img/coches/<?= $coche->getImagen() ?>" alt="<?= $coche->getNombreCompleto() ?>">
                            </div>

                            <div class="info__container">
                                <p><i class="bi bi-car-front"></i> Tracción <?= $coche->getTraccion() ?></p>
                                <p><i class="bi bi-record-circle"></i> Monta llantas de <?= $coche->getLlantas() ?>"</p>
                                <p><i class="bi bi-speedometer2"></i> Motor de <?= strtolower($coche->getMotor()) ?></p>
                                <p><i class="bi bi-gear-wide-connected"></i> Caja de cambios <?= strtolower($coche->getCambios()) ?></p>
                                <p><i class="bi bi-calendar-date"></i> Año <?= $coche->getAnio() ?></p>
                                
                                <p class="d-flex justify-content-between align-items-center mt-2 pt-2 border-top">
                                    <span>
                                        <i class="bi bi-star-fill text-warning"></i> 
                                        <strong><?= Valoracion::getMediaPuntuacion($coche->getId()) ?></strong>
                                    </span>
                                    <button type="button" class="btn btn-sm btn-link text-decoration-none p-0 text-primary-drivo fw-bold" data-bs-toggle="modal" data-bs-target="#modalOpinionesCoches<?= $coche->getId() ?>">
                                        Ver opiniones
                                    </button>
                                </p>
                            </div>

                            <div class="reservar__container">
                                <div class="price__section">
                                    <span class="price-label">PRECIO:</span>
                                    <span class="price-value"><?= $coche->getPrecioDia() ?>€/día</span>
                                </div>
                                <a href="reservar.php?id=<?= $coche->getId() ?>" class="btn__reservar">RESERVAR AHORA</a>
                            </div>
                        </div>

                        <div class="modal fade" id="modalOpinionesCoches<?= $coche->getId() ?>" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                                <div class="modal-content rounded-custom border-0 overflow-hidden shadow-lg">
                                    <div class="modal-header text-white p-4" style="background-color: #152D51;">
                                        <h5 class="modal-title fw-bold">Opiniones: <?= $coche->getNombreCompleto() ?></h5>
                                        <button type="button" class="btn-close btn-close-white shadow-none" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                    </div>
                                    <div class="modal-body p-4" style="background-color: #f2f2f2; max-height: 450px;">
                                        <?php 
                                        $lista_valoraciones = Valoracion::getValoracionesByVehiculo($coche->getId()); 
                                        if (empty($lista_valoraciones)):
                                        ?>
                                            <p class="text-muted text-center my-4">Este vehículo aún no cuenta con valoraciones.</p>
                                        <?php else: ?>
                                            <?php foreach($lista_valoraciones as $val): ?>
                                                <div class="card p-3 mb-3 border-0 shadow-sm rounded-custom bg-white text-start">
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <span class="text-warning">
                                                            <?= str_repeat('⭐', $val->getPuntuacion()) ?>
                                                        </span>
                                                        <small class="text-muted fw-bold"><?= date('d/m/Y', strtotime($val->getFechaValoracion())) ?></small>
                                                    </div>
                                                    <p class="mb-0 text-dark small">"<?= htmlspecialchars($val->getComentario()) ?>"</p>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-center w-100">No hay vehículos en nuestro catálogo en este momento.</p>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <?php include '../View/footer.php' ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>