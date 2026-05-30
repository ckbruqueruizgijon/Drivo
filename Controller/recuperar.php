<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../Model/Cliente.php';
require_once '../Model/DriveMailer.php';
require_once '../Model/drivoDB.php';

// Permite volver al paso 1 si el usuario quiere reenviar el código
if (isset($_GET['reiniciar'])) {
    unset($_SESSION['recuperar_paso'], $_SESSION['recuperar_email']);
    header('Location: recuperar.php');
    exit;
}

$paso  = $_SESSION['recuperar_paso']  ?? 1;
$email = $_SESSION['recuperar_email'] ?? '';

// ───── POST Paso 1: recibir email y enviar código ─────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'enviar_codigo') {

    $emailInput = strtolower(trim($_POST['email'] ?? ''));

    // Comprobamos que el email existe en la BD
    $conexion = DrivoDB::connectDB();
    $stmt = $conexion->prepare("SELECT id, nombre FROM clientes WHERE email = :email");
    $stmt->bindParam(':email', $emailInput);
    $stmt->execute();
    $cliente = $stmt->fetch(PDO::FETCH_OBJ);

    if ($cliente) {
        // Generamos un código de 6 dígitos
        $codigo   = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $expira   = date('Y-m-d H:i:s', strtotime('+15 minutes'));

        // Invalidamos códigos anteriores del mismo email
        $del = $conexion->prepare("DELETE FROM password_resets WHERE email = :email");
        $del->bindParam(':email', $emailInput);
        $del->execute();

        // Guardamos el nuevo código
        $ins = $conexion->prepare("INSERT INTO password_resets (email, codigo, expira_en) VALUES (:email, :codigo, :expira)");
        $ins->bindParam(':email',  $emailInput);
        $ins->bindParam(':codigo', $codigo);
        $ins->bindParam(':expira', $expira);
        $ins->execute();

        // Enviamos el email
        DriveMailer::enviarCodigo($emailInput, $cliente->nombre, $codigo);

        $_SESSION['recuperar_email'] = $emailInput;
        $_SESSION['recuperar_paso']  = 2;
    }

    // Redirigimos siempre (no revelamos si el email existe o no)
    header('Location: recuperar.php');
    exit;
}

// ───── POST Paso 2: validar código y actualizar contraseña ─────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'reset_pass') {

    $codigoInput = trim($_POST['codigo'] ?? '');
    $nuevaPass   = $_POST['nueva_pass']  ?? '';
    $confirmaPass = $_POST['confirma_pass'] ?? '';

    if ($nuevaPass !== $confirmaPass || strlen($nuevaPass) < 6) {
        header('Location: recuperar.php?error=pass');
        exit;
    }

    $conexion = DrivoDB::connectDB();
    $stmt = $conexion->prepare(
        "SELECT * FROM password_resets WHERE email = :email AND codigo = :codigo AND usado = 0 AND expira_en > NOW()"
    );
    $stmt->bindParam(':email',  $email);
    $stmt->bindParam(':codigo', $codigoInput);
    $stmt->execute();
    $registro = $stmt->fetch(PDO::FETCH_OBJ);

    if ($registro) {
        // Actualizamos la contraseña
        Cliente::updatePassword($email, $nuevaPass);

        // Marcamos el código como usado
        $upd = $conexion->prepare("UPDATE password_resets SET usado = 1 WHERE id = :id");
        $upd->bindParam(':id', $registro->id);
        $upd->execute();

        // Limpiamos la sesión del flujo de recuperación
        unset($_SESSION['recuperar_paso'], $_SESSION['recuperar_email']);

        header('Location: login.php?reset=exito');
        exit;
    } else {
        header('Location: recuperar.php?error=codigo');
        exit;
    }
}

// ───── GET: mostrar vista ─────
include '../View/login/recuperar_view.php';
