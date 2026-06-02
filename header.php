<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm py-3">
    <div class="container-fluid px-4">
        <a class="navbar-brand p-0" href="../Controller/index.php">
            <img src="../View/img/logo.png" alt="Drivo" style="width: 120px; height: auto;">
        </a>

        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#menuDrivo">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="menuDrivo">
            <ul class="navbar-nav ms-auto align-items-center mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link fw-bold px-3 text-primary-drivo" href="./index.php">Inicio</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-bold px-3 text-primary-drivo" href="./coches.php">Ver coches</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-bold px-3 text-primary-drivo" href="./reservas.php">Mis reservas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-bold px-3 text-primary-drivo" href="./contacto.php">Contactanos</a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link fw-bold px-3 text-primary-drivo" href="./ayuda.php">
                        <i class="bi bi-question-circle me-1"></i>Ayuda
                    </a>
                </li>

                <li class="nav-item ms-lg-3 mt-3 mt-lg-0">
                    <div class="icon--user d-flex justify-content-center align-items-center shadow-sm" style="width: 45px; height: 45px; border-radius: 50%; background-color: #f8f9fa;">
                        <?php if (isset($_SESSION['id_cliente'])): ?>
                            <a href="./logout.php" title="Cerrar sesión" class="text-danger">
                                <i class="bi bi-box-arrow-right fs-4"></i>
                            </a>
                        <?php else: ?>
                            <a href="./login.php" title="Iniciar sesión" class="text-primary-drivo">
                                <i class="bi bi-person-fill fs-4"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>