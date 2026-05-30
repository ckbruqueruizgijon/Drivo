<?php
session_start();
// Requerimos la conexión y el modelo de valoraciones
require_once '../Model/drivoDB.php';
require_once '../Model/Valoracion.php';

// Verificamos que los datos lleguen por POST y que el usuario tenga la sesión iniciada
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['id_cliente'])) {
    
    $id_cliente = $_SESSION['id_cliente'];
    $id_vehiculo = $_POST['id_vehiculo'];
    $puntuacion = intval($_POST['puntuacion']);
    // Limpiamos espacios en blanco y evitamos inyección de etiquetas HTML básicas
    $comentario = htmlspecialchars(trim($_POST['comentario']));

    // Doble verificación de seguridad en el servidor:
    // Aunque el botón se oculte en la vista, un usuario avanzado podría intentar mandar el POST por consola.
    // Con esto aseguramos que de verdad tiene derecho a valorar el vehículo.
    if (Valoracion::puedeValorar($id_cliente, $id_vehiculo)) {
        
        // Instanciamos el objeto con los datos recibidos del formulario
        $nuevaValoracion = new Valoracion($id_cliente, $id_vehiculo, $puntuacion, $comentario);
        
        // Insertamos el registro en la tabla 'valoraciones'
        if ($nuevaValoracion->insert()) {
            // Guardamos un mensaje de éxito en la sesión para que lo pinte tu vista de reservas
            $_SESSION['mensaje_exito'] = "¡Muchas gracias! Tu valoración se ha guardado correctamente.";
        } else {
            $_SESSION['mensaje_error'] = "Hubo un problema al guardar tu opinión. Inténtalo de nuevo.";
        }
    } else {
        $_SESSION['mensaje_error'] = "No cumples las condiciones para valorar este coche o ya has dejado una opinión.";
    }
}

// Redirigimos de vuelta al panel del cliente
header("Location: ../Controller/reservas.php");
exit();