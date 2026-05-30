<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Drivo | Iniciar Sesión</title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../View/css/style.css">
    <link rel="shortcut icon" href="../View/img/logo.png" type="image/x-icon">
    <style>
        .btn-full {
            width: 100%;
            height: 100%;
            border: none;
            background-color: #fff;
            color: #152D51;
            font-weight: bold;
            padding: 12px;
            border-radius: 0.8rem;
            transition: background 0.3s;
        }
        .btn-full:hover {
            background-color: #7BD5AB; /* Color secundario #7BD5AB */
        }

        /* Ajuste de márgenes para reducir espacio arriba y abajo */
        .login-section {
            padding-top: 6rem;    /* Espacio superior moderado */
            padding-bottom: 10rem; /* Espacio inferior moderado */
            display: flex;
            justify-content: center;
            align-items: center;
        }
    </style>
</head>
<body>
    <?php include '../View/header.php' ?>

    <!-- Se ha quitado min-vh-100 y se ha puesto la nueva clase login-section -->
    <main class="main__container login-section">
        <div class="oferta p-4" style="width: 100%; max-width: 400px; position: relative;">
            <h2 class="modelo" style="position: absolute; top: -15px; left: 50%; transform: translateX(-50%); width: 220px;">
                LOGIN
            </h2>
            
            <?php if (isset($_GET['error']) && $_GET['error'] === 'auth'): ?>
                <div class="alert alert-danger text-center mt-3 mb-0" role="alert">
                    <i class="bi bi-exclamation-triangle-fill"></i> Email o contraseña incorrectos.
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['registro']) && $_GET['registro'] === 'exito'): ?>
                <div class="alert alert-success text-center mt-3 mb-0" role="alert">
                    <i class="bi bi-check-circle-fill"></i> Registro exitoso. Ahora puedes iniciar sesión.
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['reset']) && $_GET['reset'] === 'exito'): ?>
                <div class="alert alert-success text-center mt-3 mb-0" role="alert">
                    <i class="bi bi-check-circle-fill"></i> Contraseña restablecida correctamente. Ya puedes entrar.
                </div>
            <?php endif; ?>
            
            <form action="../Controller/login.php" method="POST" class="mt-4 pt-3 needs-validation" novalidate>
                
                <div class="mb-3">
                    <label for="email" class="form-label fw-bold">Email</label>
                    <input type="text" class="form-control" id="email" name="email" 
                           placeholder="Introduce tu correo" 
                           style="border-radius: 0.8rem;" required>
                    <div class="invalid-feedback">
                        Por favor, introduce tu email.
                    </div>
                </div>

                <div class="mb-4">
                    <label for="password" class="form-label fw-bold">Contraseña</label>
                    <input type="password" class="form-control" id="password" name="password" 
                           placeholder="••••••••" 
                           style="border-radius: 0.8rem;" required>
                    <div class="invalid-feedback">
                        La contraseña es obligatoria.
                    </div>
                    <div class="text-end mt-1">
                        <a href="recuperar.php" class="link" style="font-size:0.82rem;">¿Olvidaste tu contraseña?</a>
                    </div>
                </div>
                
                <div class="reservar__container" style="margin: 0; padding: 5px;">
                    <button type="submit" class="btn-full">
                        ENTRAR A MI CUENTA
                    </button>
                </div>
                
                <p class="text-center mt-3" style="font-size: 0.9rem;">
                    ¿Nuevo en Drivo? <a href="registro.php" class="link" style="font-weight: bold;">Crea una cuenta</a>
                </p>
            </form>
        </div>
    </main>

    <?php include '../View/footer.php' ?>

    <script>
        (() => {
          'use strict'
          const forms = document.querySelectorAll('.needs-validation')
          Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
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
