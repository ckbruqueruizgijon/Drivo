<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Drivo | Reservar Vehículo</title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../View/css/style.css">
    <link rel="stylesheet" href="../View/css/reservar.css">
    <link rel="shortcut icon" href="../View/img/logo.png" type="image/x-icon">
</head>
<body>
    <?php include '../View/header.php' ?>

    <main class="main__container reserva-container">
        
        <?php if (isset($_SESSION['mensaje_error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show rounded-custom" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> <?= $_SESSION['mensaje_error'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['mensaje_error']); ?>
        <?php endif; ?>

        <div class="row g-5">
            <!-- Columna Izquierda: Imagen y Detalles -->
            <div class="col-lg-7">
                <div class="oferta oferta-detalle p-0 w-100">
                    <img src="../View/img/coches/<?= pathinfo($coche->getImagen(), PATHINFO_FILENAME) ?>--sin_fondo.png" class="img-reserva" alt="<?= $coche->getNombreCompleto() ?>">
                    
                    <div class="p-4">
                        <h2 class="text-uppercase fw-bold mb-3 text-primary-drivo"><?= $coche->getNombreCompleto() ?></h2>
                        <div class="row info__container p-0">
                            <div class="col-md-6">
                                <p><i class="bi bi-car-front"></i> Tracción <?= $coche->getTraccion() ?></p>
                                <p><i class="bi bi-speedometer2"></i> Motor <?= $coche->getMotor() ?></p>
                            </div>
                            <div class="col-md-6">
                                <p><i class="bi bi-gear-wide-connected"></i> Cambio <?= $coche->getCambios() ?></p>
                                <p><i class="bi bi-calendar-date"></i> Año <?= $coche->getAnio() ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Columna Derecha: Formulario de Reserva -->
            <div class="col-lg-5">
                <div class="oferta p-4 w-100 position-relative">
                    <h2 class="modelo modelo-reserva">
                        TU RESERVA
                    </h2>

                    <form action="../Controller/pago.php" method="POST" class="mt-4 needs-validation" novalidate>
                        
                        <!-- Cuadrado de aviso (oculto por defecto) -->
                        <div id="alerta_reserva" class="alert alert-warning d-none rounded-custom mt-2" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i> <span id="mensaje_alerta"></span>
                        </div>

                        <input type="hidden" name="id_coche" value="<?= $coche->getId() ?>">
                        
                        <div class="mb-3 mt-3">
                            <label class="form-label fw-bold">Fecha de Recogida</label>
                            <input type="date" id="fecha_inicio" name="fecha_inicio" class="form-control rounded-custom" 
                                   required onchange="validarFechas()">
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Fecha de Devolución</label>
                            <input type="date" id="fecha_fin" name="fecha_fin" class="form-control rounded-custom" 
                                   required onchange="calcularTotal()">
                        </div>

                        <!-- Sección de Precios Dinámica -->
                        <div class="price-summary mb-4 p-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="fw-bold text-primary-drivo small">PRECIO POR DÍA</span>
                                <span class="fw-bold text-primary-drivo"><?= $coche->getPrecioDia() ?>€</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-bold text-primary-drivo">PRECIO TOTAL</span>
                                <span id="precio_total" class="fw-bold text-secondary-drivo fs-4">0€</span>
                            </div>
                        </div>

                        <div class="reservar__container m-0 p-1">
                            <button type="submit" class="btn-full">
                                CONFIRMAR RESERVA
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <?php include '../View/footer.php' ?>

    <script>
        const precioPorDia = <?= $coche->getPrecioDia() ?>;
        const reservasActivas = <?= $reservasJson ?? '[]' ?>;

        // Pongo como minimo la fecha de hoy para que no se puedan elegir dias pasados
        const hoy = new Date().toISOString().split('T')[0];
        document.getElementById('fecha_inicio').min = hoy;

        // Comprueba si las fechas elegidas se pisan con alguna reserva que ya existe
        function checkOverlap(start, end) {
            if (!start || !end) return null;
            
            for (let reserva of reservasActivas) {
                if (start <= reserva.fin && end >= reserva.inicio) {
                    return reserva; // Devuelve la reserva con la que choca
                }
            }
            return null;
        }

        function validarFechas() {
            const fInicio = document.getElementById('fecha_inicio');
            const fFin = document.getElementById('fecha_fin');
            
            // La fecha de fin no puede ser antes que la de inicio
            fFin.min = fInicio.value;
            
            if (fFin.value && fFin.value < fInicio.value) {
                fFin.value = fInicio.value;
            }
            calcularTotal();
        }

        function calcularTotal() {
            const inicioInput = document.getElementById('fecha_inicio');
            const finInput = document.getElementById('fecha_fin');
            const totalDisplay = document.getElementById('precio_total');
            const btnSubmit = document.querySelector('.btn-full');
            const alertaReserva = document.getElementById('alerta_reserva');
            const mensajeAlerta = document.getElementById('mensaje_alerta');

            // Reseteo los errores antes de volver a comprobar
            inicioInput.classList.remove('is-invalid');
            finInput.classList.remove('is-invalid');
            alertaReserva.classList.add('d-none');
            inicioInput.setCustomValidity('');
            finInput.setCustomValidity('');
            btnSubmit.disabled = false;

            if (inicioInput.value && finInput.value) {
                const overlap = checkOverlap(inicioInput.value, finInput.value);
                if (overlap) {
                    // Calcular el próximo día libre
                    let nextFree = new Date(overlap.fin);
                    nextFree.setDate(nextFree.getDate() + 1);
                    let nextFreeStr = nextFree.toISOString().split('T')[0];
                    
                    // Asegurarnos de que el día siguiente no esté ocupado por OTRA reserva
                    let overlapsAgain = true;
                    while(overlapsAgain) {
                        overlapsAgain = false;
                        for (let r of reservasActivas) {
                            if (nextFreeStr >= r.inicio && nextFreeStr <= r.fin) {
                                nextFree = new Date(r.fin);
                                nextFree.setDate(nextFree.getDate() + 1);
                                nextFreeStr = nextFree.toISOString().split('T')[0];
                                overlapsAgain = true;
                                break;
                            }
                        }
                    }

                    // Formateo la fecha a DD/MM/YYYY para que sea mas legible
                    const partes = nextFreeStr.split('-');
                    const fechaAmigable = `${partes[2]}/${partes[1]}/${partes[0]}`;

                    // Mostrar cuadrado de alerta arriba
                    const mensaje = `Este vehículo está ocupado. Estará libre de nuevo a partir del ${fechaAmigable}.`;
                    mensajeAlerta.innerText = mensaje;
                    alertaReserva.classList.remove('d-none');
                    
                    // También marcamos en rojo para que vean dónde está el error
                    inicioInput.classList.add('is-invalid');
                    finInput.classList.add('is-invalid');
                    
                    inicioInput.setCustomValidity('Fechas solapadas');
                    finInput.setCustomValidity('Fechas solapadas');
                    btnSubmit.disabled = true;
                    totalDisplay.innerText = "0€";
                    return;
                }

                const fecha1 = new Date(inicioInput.value);
                const fecha2 = new Date(finInput.value);
                
                // Calculo cuantos dias son entre las dos fechas
                const diffTime = fecha2 - fecha1;
                // Le sumo 1 porque el dia de recogida tambien cuenta
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;

                if (diffDays > 0) {
                    totalDisplay.innerText = (diffDays * precioPorDia) + "€";
                } else {
                    totalDisplay.innerText = "0€";
                }
            } else {
                totalDisplay.innerText = "0€";
            }
        }

        // Validación de Bootstrap
        (() => {
          'use strict'
          const forms = document.querySelectorAll('.needs-validation')
          Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
              const fInicio = document.getElementById('fecha_inicio').value;
              const fFin = document.getElementById('fecha_fin').value;
              
              if (checkOverlap(fInicio, fFin)) {
                  event.preventDefault();
                  event.stopPropagation();
                  document.getElementById('fecha_inicio').classList.add('is-invalid');
                  document.getElementById('fecha_fin').classList.add('is-invalid');
                  return;
              }

              if (!form.checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
              }
              form.classList.add('was-validated')
            }, false)
          })
        })()
    </script>
</body>
</html>
