<?php
require_once 'auth_admin.php';
require_once '../../Model/Vehiculo.php';

$uploadDir = __DIR__ . '/../../View/img/coches/';
$allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/avif'];

/**
 * Llama a la API de remove.bg para eliminar el fondo de una imagen.
 * Guarda el resultado como PNG en la misma carpeta con el sufijo --sin_fondo.
 *
 * @param string $rutaImagenOriginal  Ruta absoluta del archivo ya guardado
 * @param string $nombreBase          Nombre sin extensión (ej: "audi_a4")
 * @param string $uploadDir           Carpeta de destino
 * @return bool  true si se guardó correctamente, false en caso de error
 */
function quitarFondoRemoveBg(string $rutaImagenOriginal, string $nombreBase, string $uploadDir): bool
{
    $apiKey   = 'p4ByW4RdFERBc1jevwBQhEEg';
    $endpoint = 'https://api.remove.bg/v1.0/removebg';

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL            => $endpoint,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => [
            'image_file' => new CURLFile($rutaImagenOriginal),
            'size'       => 'auto',
        ],
        CURLOPT_HTTPHEADER     => [
            'X-Api-Key: ' . $apiKey,
        ],
    ]);

    $respuesta  = curl_exec($ch);
    $httpCode   = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode !== 200 || empty($respuesta)) {
        return false;
    }

    $nombreSinFondo = $nombreBase . '--sin_fondo.png';
    return (bool) file_put_contents($uploadDir . $nombreSinFondo, $respuesta);
}

// ───── POST: guardar (insert o update) ─────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id          = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT); // null = insertar
    $matricula   = trim($_POST['matricula']  ?? '');
    $marca       = trim($_POST['marca']      ?? '');
    $modelo      = trim($_POST['modelo']     ?? '');
    $motor       = trim($_POST['motor']      ?? '');
    $cambios     = trim($_POST['cambios']    ?? '');
    $traccion    = trim($_POST['traccion']   ?? '');
    $llantas     = intval($_POST['llantas']  ?? 17);
    $anio        = intval($_POST['anio']     ?? date('Y'));
    $precio_dia  = floatval($_POST['precio_dia'] ?? 0);
    $disponible  = isset($_POST['disponible']) ? 1 : 0;
    $oferta      = isset($_POST['oferta'])     ? 1 : 0;

    // Gestión de imagen
    $imagenActual = trim($_POST['imagen_actual'] ?? 'default.jpg');
    $imagen = $imagenActual;

    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['imagen'];
        if (in_array($file['type'], $allowedTypes) && $file['size'] <= 5 * 1024 * 1024) {
            $extension  = pathinfo($file['name'], PATHINFO_EXTENSION);

            // Nombre base limpio: marca_modelo (sin extensión)
            $nombreBase = strtolower($marca . '_' . $modelo);
            $nombreBase = preg_replace('/[^a-z0-9_-]/', '_', $nombreBase);

            // Imagen original: audi_a4.jpg
            $nombreArchivo = $nombreBase . '.' . $extension;

            $rutaDestino = $uploadDir . $nombreArchivo;
            if (move_uploaded_file($file['tmp_name'], $rutaDestino)) {
                $imagen = $nombreArchivo;

                // Imagen sin fondo: audi_a4--sin_fondo.png
                quitarFondoRemoveBg($rutaDestino, $nombreBase, $uploadDir);
            }
        }
    }

    $vehiculo = new Vehiculo($matricula, $marca, $modelo, $motor, $cambios, $traccion,
                             $llantas, $anio, $precio_dia, $imagen, $disponible, $oferta, $id);

    if ($id) {
        $ok = $vehiculo->update();
        header('Location: vehiculos.php?ok=' . ($ok ? 'editado' : 'error'));
    } else {
        $ok = $vehiculo->insert();
        header('Location: vehiculos.php?ok=' . ($ok ? 'creado' : 'error'));
    }
    exit;
}

// ───── GET: mostrar formulario ─────
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$vehiculoEditar = $id ? Vehiculo::getById($id) : null;

$paginaActiva = 'vehiculos';
include '../../View/admin/vehiculo_form_view.php';
