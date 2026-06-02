<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Drivo | Registro</title>
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
        
        .form-control {
            border-radius: 0.8rem;
            border: 1px solid #ccc;
        }

        /* Clase para asegurar que el contenido no toque el footer */
        .spacer-bottom {
            margin-bottom: 100px; /* Ajusta este valor si quieres más o menos espacio */
        }
    </style>
</head>
<body>
    <?php include '../View/header.php' ?>

    <!-- Añadido mb-5 y spacer-bottom para separar del footer -->
    <main class="main__container d-flex justify-content-center align-items-center py-5 spacer-bottom">
        <div class="oferta p-4" style="width: 100%; max-width: 600px; position: relative;">
            <h2 class="modelo" style="position: absolute; top: -15px; left: 50%; transform: translateX(-50%); width: 250px;">
                REGISTRO DRIVO
            </h2>
            
            <?php if (isset($_GET['error']) && $_GET['error'] === 'duplicado'): ?>
                <div class="alert alert-warning text-center mt-3 mb-0" role="alert">
                    <i class="bi bi-exclamation-circle-fill"></i> El correo electrónico o usuario ya está registrado.
                </div>
            <?php endif; ?>
            
            <form action="../Controller/registro.php" method="POST" class="mt-4 pt-3 row g-3 needs-validation" novalidate>
                
                <div class="col-md-6">
                    <label for="nombre" class="form-label fw-bold">Nombre</label>
                    <input type="text" name="nombre" id="nombre" class="form-control" required>
                    <div class="invalid-feedback">Dinos tu nombre.</div>
                </div>

                <div class="col-md-6">
                    <label for="apellidos" class="form-label fw-bold">Apellidos</label>
                    <input type="text" name="apellidos" id="apellidos" class="form-control" required>
                    <div class="invalid-feedback">Dinos tus apellidos.</div>
                </div>

                <div class="col-12">
                    <label for="emailReg" class="form-label fw-bold">Correo Electrónico</label>
                    <input type="email" name="email" id="emailReg" class="form-control" required>
                    <div class="invalid-feedback">Introduce un correo válido.</div>
                </div>

                <div class="col-md-6">
                    <label for="password" class="form-label fw-bold">Contraseña</label>
                    <input type="password" name="password" id="password" class="form-control" minlength="6" required>
                    <div class="invalid-feedback">Mínimo 6 caracteres.</div>
                </div>

                <div class="col-md-6">
                    <label for="confirm_password" class="form-label fw-bold">Repetir Contraseña</label>
                    <input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
                    <div class="invalid-feedback">Confirma tu contraseña.</div>
                </div>
                
                <div class="col-12 mt-4">
                    <div class="reservar__container" style="margin: 0; padding: 5px;">
                        <button type="submit" class="btn-full">
                            CREAR MI CUENTA AHORA
                        </button>
                    </div>
                </div>

                <p class="text-center mt-3 mb-0" style="font-size: 0.9rem;">
                    ¿Ya tienes cuenta? <a href="login.php" class="link" style="font-weight: bold;">Inicia sesión aquí</a>
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

        document.getElementById('emailReg').addEventListener('blur', function() {
            this.value = this.value.toLowerCase().trim();
        });
    </script>
</body>
</html>
