<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Drivo | Recuperar contraseña</title>
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
        .btn-full:hover { background-color: #7BD5AB; }

        .recuperar-section {
            padding-top: 6rem;
            padding-bottom: 10rem;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .codigo-input {
            font-size: 2rem;
            letter-spacing: 0.5rem;
            text-align: center;
            font-weight: bold;
            border-radius: 0.8rem !important;
        }
    </style>
</head>
<body>
    <?php include '../View/header.php' ?>

    <main class="main__container recuperar-section">
        <div class="oferta p-4" style="width: 100%; max-width: 420px; position: relative;">

            <?php
            $paso  = $_SESSION['recuperar_paso']  ?? 1;
            $error = $_GET['error'] ?? null;
            ?>

            <?php if ($paso === 1): ?>
                <!-- ── PASO 1: Introducir email ── -->
                <h2 class="modelo" style="position: absolute; top: -15px; left: 50%; transform: translateX(-50%); width: 260px; white-space: nowrap;">
                    RECUPERAR CUENTA
                </h2>

                <?php if ($error === 'pass'): ?>
                    <div class="alert alert-danger text-center mt-4 mb-0" role="alert">
                        <i class="bi bi-exclamation-triangle-fill"></i> Las contraseñas no coinciden o son demasiado cortas.
                    </div>
                <?php endif; ?>

                <p class="text-center mt-4 pt-2" style="color:#555; font-size:0.9rem;">
                    Introduce tu email y te enviaremos un código para restablecer tu contraseña.
                </p>

                <form action="../Controller/recuperar.php" method="POST" class="mt-3 needs-validation" novalidate>
                    <input type="hidden" name="accion" value="enviar_codigo">

                    <div class="mb-4">
                        <label for="email_recuperar" class="form-label fw-bold">Email de tu cuenta</label>
                        <input type="email" class="form-control" id="email_recuperar" name="email"
                               placeholder="correo@ejemplo.com"
                               style="border-radius: 0.8rem;" required>
                        <div class="invalid-feedback">Introduce un email válido.</div>
                    </div>

                    <div class="alert alert-info" style="font-size:0.82rem; border-radius:0.8rem;">
                        <i class="bi bi-envelope-fill me-1"></i>
                        Si el email está registrado, recibirás un código en tu bandeja de entrada.
                    </div>

                    <div class="reservar__container" style="margin: 0; padding: 5px;">
                        <button type="submit" class="btn-full">
                            <i class="bi bi-send me-2"></i> ENVIAR CÓDIGO
                        </button>
                    </div>

                    <p class="text-center mt-3" style="font-size: 0.9rem;">
                        <a href="login.php" class="link" style="font-weight: bold;">
                            <i class="bi bi-arrow-left me-1"></i> Volver al login
                        </a>
                    </p>
                </form>

            <?php else: ?>
                <!-- ── PASO 2: Introducir código + nueva contraseña ── -->
                <h2 class="modelo" style="position: absolute; top: -15px; left: 50%; transform: translateX(-50%); width: 240px; white-space: nowrap;">
                    NUEVA CONTRASEÑA
                </h2>

                <?php if ($error === 'codigo'): ?>
                    <div class="alert alert-danger text-center mt-4 mb-0" role="alert">
                        <i class="bi bi-x-circle-fill"></i> Código incorrecto o caducado. Inténtalo de nuevo.
                    </div>
                <?php endif; ?>
                <?php if ($error === 'pass'): ?>
                    <div class="alert alert-danger text-center mt-4 mb-0" role="alert">
                        <i class="bi bi-exclamation-triangle-fill"></i> Las contraseñas no coinciden o son demasiado cortas (mín. 6 caracteres).
                    </div>
                <?php endif; ?>

                <p class="text-center mt-4 pt-2" style="color:#555; font-size:0.9rem;">
                    Hemos enviado un código de 6 dígitos a <strong><?= htmlspecialchars($_SESSION['recuperar_email'] ?? '') ?></strong>.
                    Introduce el código y tu nueva contraseña.
                </p>

                <form action="../Controller/recuperar.php" method="POST" class="mt-3 needs-validation" novalidate id="form-reset">
                    <input type="hidden" name="accion" value="reset_pass">

                    <div class="mb-3">
                        <label for="codigo_input" class="form-label fw-bold text-center d-block">Código de verificación</label>
                        <input type="text" class="form-control codigo-input" id="codigo_input" name="codigo"
                               maxlength="6" pattern="\d{6}" placeholder="000000" required
                               autocomplete="one-time-code" inputmode="numeric">
                        <div class="invalid-feedback text-center">Introduce el código de 6 dígitos.</div>
                    </div>

                    <div class="mb-3">
                        <label for="nueva_pass" class="form-label fw-bold">Nueva contraseña</label>
                        <input type="password" class="form-control" id="nueva_pass" name="nueva_pass"
                               placeholder="Mínimo 6 caracteres" style="border-radius:0.8rem;" minlength="6" required>
                    </div>

                    <div class="mb-4">
                        <label for="confirma_pass" class="form-label fw-bold">Confirmar contraseña</label>
                        <input type="password" class="form-control" id="confirma_pass" name="confirma_pass"
                               placeholder="Repite la contraseña" style="border-radius:0.8rem;" minlength="6" required>
                        <div class="invalid-feedback" id="pass-error" style="display:none;">Las contraseñas no coinciden.</div>
                    </div>

                    <div class="reservar__container" style="margin: 0; padding: 5px;">
                        <button type="submit" class="btn-full">
                            <i class="bi bi-lock-fill me-2"></i> RESTABLECER CONTRASEÑA
                        </button>
                    </div>

                    <p class="text-center mt-3" style="font-size: 0.85rem;">
                        ¿No recibiste el código?
                        <a href="../Controller/recuperar.php?reiniciar=1" class="link fw-bold">Volver a enviar</a>
                    </p>
                </form>
            <?php endif; ?>

        </div>
    </main>

    <?php include '../View/footer.php' ?>

    <script>
        // Validación Bootstrap
        (() => {
            'use strict';
            const forms = document.querySelectorAll('.needs-validation');
            Array.from(forms).forEach(form => {
                form.addEventListener('submit', event => {
                    // Comprobamos que las contraseñas coinciden
                    const p1 = document.getElementById('nueva_pass');
                    const p2 = document.getElementById('confirma_pass');
                    const err = document.getElementById('pass-error');
                    if (p1 && p2 && p1.value !== p2.value) {
                        event.preventDefault();
                        event.stopPropagation();
                        if (err) { err.style.display = 'block'; }
                        p2.classList.add('is-invalid');
                    }
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        })();
    </script>
</body>
</html>
