<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Drivo | Pago Seguro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../View/css/style.css">
    <link rel="stylesheet" href="../View/css/pago.css">
    <link rel="shortcut icon" href="../View/img/logo.png" type="image/x-icon">
</head>
<body class="bg-white">
    <?php include '../View/header.php' ?>

    <main class="main__container pago-container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="row g-0 card-pago shadow-lg">
                    
                    <div class="col-12 col-md-5 order-1 p-0">
                        <div class="resumen-reserva h-100 p-4 p-md-5 bg-primary-drivo text-white">
                            <h3 class="fw-bold mb-4 text-secondary-drivo">Tu Reserva</h3>
                            
                            <div class="text-center mb-4">
                                <img src="../View/img/coches/<?= pathinfo($coche->getImagen(), PATHINFO_FILENAME) ?>--sin_fondo.png" 
                                     alt="<?= $coche->getNombreCompleto() ?>" 
                                     class="resumen-img img-fluid">
                            </div>
                            
                            <h4 class="mb-4 text-center text-md-start"><?= $coche->getNombreCompleto() ?></h4>
                            
                            <div class="resumen-detalles">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="opacity-75">Recogida</span>
                                    <span class="fw-bold"><?= date('d/m/Y', strtotime($fecha_inicio)) ?></span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="opacity-75">Devolución</span>
                                    <span class="fw-bold"><?= date('d/m/Y', strtotime($fecha_fin)) ?></span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="opacity-75">Días totales</span>
                                    <span><?= $dias ?></span>
                                </div>
                                <div class="d-flex justify-content-between mb-4">
                                    <span class="opacity-75">Precio por día</span>
                                    <span><?= $coche->getPrecioDia() ?>€</span>
                                </div>
                                
                                <hr class="border-light opacity-25">
                                
                                <div class="d-flex justify-content-between align-items-center mt-4">
                                    <span class="fs-5 opacity-75">Total</span>
                                    <span class="fs-2 fw-bold text-secondary-drivo"><?= $precio_total ?>€</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-7 order-2 p-4 p-md-5 bg-white">
                        <h2 class="fw-bold mb-4 text-primary-drivo">Detalles de Pago</h2>
                        <p class="text-muted mb-4">Introduce los datos de tu tarjeta para finalizar la reserva.</p>

                        <form action="../Controller/procesar_pago.php" method="POST" class="needs-validation" novalidate>
                            <input type="hidden" name="id_coche" value="<?= $coche->getId() ?>">
                            <input type="hidden" name="fecha_inicio" value="<?= $fecha_inicio ?>">
                            <input type="hidden" name="fecha_fin" value="<?= $fecha_fin ?>">
                            <input type="hidden" name="precio_total" value="<?= $precio_total ?>">

                            <div class="mb-4">
                                <label class="form-label fw-bold">Titular de la tarjeta</label>
                                <div class="input-group has-validation">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-person"></i></span>
                                    <input type="text" name="titular" class="form-control bg-light border-start-0 ps-0" placeholder="Nombre completo como aparece" pattern="^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]{5,40}$" required>
                                    <div class="invalid-feedback">Introduce el nombre completo del titular (mínimo 5 letras, sin números).</div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold">Número de tarjeta</label>
                                <div class="input-group has-validation">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-credit-card"></i></span>
                                    <input type="text" id="num_tarjeta" name="num_tarjeta" class="form-control bg-light border-start-0 ps-0" placeholder="0000 0000 0000 0000" inputmode="numeric" pattern="^[0-9]{13,16}$" maxlength="16" required>
                                    <div class="invalid-feedback">El número de tarjeta debe contener entre 13 y 16 dígitos numéricos.</div>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-6">
                                    <label class="form-label fw-bold">Expiración</label>
                                    <input type="text" id="expiracion" name="expiracion" class="form-control bg-light" placeholder="MM/YY" pattern="^(0[1-9]|1[0-2])\/([0-9]{2})$" maxlength="5" inputmode="numeric" required>
                                    <div id="error-expiracion" class="invalid-feedback">Formato requerido inválido (MM/YY).</div>
                                </div>
                                <div class="col-6">
                                    <label class="form-label fw-bold">CVV</label>
                                    <input type="password" name="cvv" class="form-control bg-light" placeholder="123" pattern="^[0-9]{3,4}$" maxlength="4" inputmode="numeric" required>
                                    <div class="invalid-feedback">Código de seguridad inválido (3 o 4 dígitos).</div>
                                </div>
                            </div>

                            <div class="p-3 mb-4 rounded-custom bg-light d-flex align-items-center">
                                <i class="bi bi-shield-check text-success fs-4 me-3"></i>
                                <span class="small text-muted">Tu pago está protegido con encriptación SSL de 256 bits.</span>
                            </div>

                            <button type="submit" class="btn-pay w-100">
                                PAGAR <?= $precio_total ?>€ AHORA
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </main>

    <?php include '../View/footer.php' ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        (() => {
          'use strict'
          const forms = document.querySelectorAll('.needs-validation')
          const expInput = document.getElementById('expiracion');
          const errorFeedback = document.getElementById('error-expiracion');

          Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
              
              // --- NUEVA CAPA DE VALIDACIÓN TEMPORAL (TARJETA CADUCADA) ---
              if (expInput && expInput.value.length === 5) {
                  const partes = expInput.value.split('/');
                  const mesInput = parseInt(partes[0], 10);
                  // Convertimos el año '26' en entero completo '2026'
                  const anioInput = parseInt('20' + partes[1], 10);

                  // Obtener la fecha actual del sistema
                  const fechaActual = new Date();
                  const mesActual = fechaActual.getMonth() + 1; // getMonth() va de 0 a 11
                  const anioActual = fechaActual.getFullYear();

                  // Comparamos si el año es menor, o si siendo el mismo año, el mes ya pasó
                  if (anioInput < anioActual || (anioInput === anioActual && mesInput < mesActual)) {
                      // Forzamos el fallo del campo usando el sistema nativo de HTML5
                      expInput.setCustomValidity("Tarjeta caducada");
                      if (errorFeedback) {
                          errorFeedback.innerText = "La tarjeta introducida está caducada.";
                      }
                  } else {
                      // Si todo está correcto, limpiamos cualquier error personalizado previo
                      expInput.setCustomValidity("");
                  }
              }

              if (!form.checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
              }
              form.classList.add('was-validated')
            }, false)
          })

          // Máscara interactiva automática para el campo Expiración (Añade la barra '/' sola)
          if(expInput) {
              expInput.addEventListener('input', function(e) {
                  // Cada vez que el usuario vuelve a escribir, limpiamos el error personalizado
                  expInput.setCustomValidity("");
                  if (errorFeedback) {
                      errorFeedback.innerText = "Formato requerido inválido (MM/YY).";
                  }

                  let valor = e.target.value.replace(/\D/g, ''); 
                  if (valor.length > 2) {
                      e.target.value = valor.substring(0, 2) + '/' + valor.substring(2, 4);
                  } else {
                      e.target.value = valor;
                  }
              });
          }

          // Filtro interactivo para evitar que escriban letras en el número de tarjeta directamente
          const tarjetaInput = document.getElementById('num_tarjeta');
          if(tarjetaInput) {
              tarjetaInput.addEventListener('input', function(e) {
                  e.target.value = e.target.value.replace(/\D/g, '');
              });
          }
        })()
    </script>
</body>
</html>