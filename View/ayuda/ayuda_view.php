<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Drivo | Centro de Ayuda</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../View/css/style.css">
    <link rel="shortcut icon" href="../View/img/logo.png" type="image/x-icon">
    
    <style>
        .ayuda-container {
            padding-top: 4rem;
            padding-bottom: 6rem;
            background-color: #f2f2f2 !important;
        }
        .search-hero {
            background-color: #152D51;
            color: white;
            border-radius: 1.5rem;
            padding: 3rem 2rem;
        }
        .accordion-item {
            border: none;
            margin-bottom: 1rem;
            border-radius: 1rem !important;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            overflow: hidden;
        }
        .accordion-button {
            font-weight: 600;
            color: #152D51 !important;
            background-color: #ffffff !important;
            padding: 1.2rem;
        }
        .accordion-button:not(.collapsed) {
            background-color: #f8f9fa !important;
            box-shadow: none;
        }
        .accordion-button::after {
            font-family: "bootstrap-icons";
            content: "\b2a4";
            background-image: none;
        }
        .accordion-button:not(.collapsed)::after {
            content: "\b2a7";
            background-image: none;
        }
        .card-guia-rapida {
            border: none;
            border-radius: 1rem;
            transition: transform 0.2s;
        }
        .card-guia-rapida:hover {
            transform: translateY(-5px);
        }
        .icon-box {
            width: 50px;
            height: 50px;
            background-color: #7BD5AB;
            color: #152D51;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>
<body>
    <?php include '../View/header.php' ?>

    <main class="main__container ayuda-container">
        <div class="container px-4">
            
            <div class="search-hero text-center mb-5 shadow">
                <h1 class="fw-bold mb-3">¿En qué podemos ayudarte?</h1>
                <p class="opacity-75 mb-4">Guía rápida de acciones básicas para moverte con Drivo</p>
                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-6">
                        <div class="input-group shadow-sm">
                            <span class="input-group-text bg-white border-0"><i class="bi bi-search text-muted"></i></span>
                            <input type="text" id="ayudaSearch" class="form-control border-0 p-3" placeholder="Busca una acción (ej. reservar, cambiar fecha...)" onkeyup="filtrarAyuda()">
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-lg-4">
                    <h4 class="fw-bold text-primary-drivo mb-4 text-uppercase small" style="letter-spacing: 1px;">Guías Rápidas</h4>
                    
                    <div class="card card-guia-rapida p-3 mb-3 shadow-sm bg-white">
                        <div class="d-flex align-items-center">
                            <div class="icon-box me-3"><i class="bi bi-car-front-fill fs-4"></i></div>
                            <div>
                                <h6 class="fw-bold text-primary-drivo mb-1">Alquilar un coche</h6>
                                <p class="text-muted small mb-0">Pasos para confirmar tu primer coche.</p>
                            </div>
                        </div>
                    </div>

                    <div class="card card-guia-rapida p-3 mb-3 shadow-sm bg-white">
                        <div class="d-flex align-items-center">
                            <div class="icon-box me-3"><i class="bi bi-calendar-event fs-4"></i></div>
                            <div>
                                <h6 class="fw-bold text-primary-drivo mb-1">Modificar Fechas</h6>
                                <p class="text-muted small mb-0">Políticas de recargos y cambios.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8">
                    <h4 class="fw-bold text-primary-drivo mb-4 text-uppercase small" style="letter-spacing: 1px;">Acciones Frecuentes</h4>
                    
                    <div class="accordion" id="accordionAyuda">
                        
                        <div class="accordion-item item-ayuda">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#ayuda1">
                                    <i class="bi bi-search me-2 text-secondary-drivo"></i> ¿Cómo busco y selecciono un vehículo?
                                </button>
                            </h2>
                            <div id="ayuda1" class="accordion-collapse collapse show" data-bs-parent="#accordionAyuda">
                                <div class="accordion-body text-muted">
                                    Dirígete a la sección <strong>"Ver coches"</strong> desde el menú superior. Allí podrás ver nuestra flota disponible. Cada vehículo cuenta con una tarjeta informativa donde se detalla su motor, tipo de cambio, tracción y el precio por día. Haz clic en "Reservar" en el coche que más te guste.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item item-ayuda">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#ayuda2">
                                    <i class="bi bi-credit-card me-2 text-secondary-drivo"></i> ¿Cómo realizo el pago de la reserva?
                                </button>
                            </h2>
                            <div id="ayuda2" class="accordion-collapse collapse" data-bs-parent="#accordionAyuda">
                                <div class="accordion-body text-muted">
                                    Una vez selecciones las fechas de recogida y devolución en la pantalla del coche, el sistema calculará el precio total. Al pulsar "Confirmar", pasarás a la pasarela de <strong>Pago Seguro</strong>, donde deberás introducir los datos de tu tarjeta de crédito o débito. El proceso cuenta con encriptación SSL de seguridad.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item item-ayuda">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#ayuda3">
                                    <i class="bi bi-calendar-range me-2 text-secondary-drivo"></i> ¿Puedo cambiar las fechas de mi reserva?
                                </button>
                            </h2>
                            <div id="ayuda3" class="accordion-collapse collapse" data-bs-parent="#accordionAyuda">
                                <div class="accordion-body text-muted">
                                    ¡Sí! Ve a <strong>"Mis reservas"</strong>, selecciona la reserva que quieras editar y cambia las fechas en los selectores. El precio se recalculará en tiempo real. 
                                    <br><br>
                                    <span class="text-danger"><strong>Nota sobre recargos:</strong></span> Si modificas la reserva con menos de 24 horas de antelación al inicio del renting, se aplicará una penalización del 20% sobre el coste diario por falta de aviso.
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </main>

    <?php include '../View/footer.php' ?>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>


    <script>
    function filtrarAyuda() {
        let input = document.getElementById('ayudaSearch').value.toLowerCase();
        let items = document.querySelectorAll('.item-ayuda');
        
        items.forEach(function(item) {
            let texto = item.innerText.toLowerCase();
            if (texto.includes(input)) {
                item.classList.remove('d-none');
            } else {
                item.classList.add('d-none');
            }
        });
    }
    </script>
</body>
</html>