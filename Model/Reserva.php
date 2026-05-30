<?php
require_once 'drivoDB.php';
require_once 'Vehiculo.php';

class Reserva {
    private $id;
    private $id_vehiculo;
    private $id_cliente;
    private $fecha_reserva;
    private $fecha_inicio;
    private $fecha_fin;
    private $sancion_km;
    private $sancion_tiempo;
    private $precio_total;
    private $estado;

    // Este campo no esta en la tabla pero me viene bien para mostrar datos del coche
    private $vehiculoAsociado;

    public function __construct($id_vehiculo, $id_cliente, $fecha_inicio, $fecha_fin, $precio_total, $estado = 'Pendiente', $sancion_km = 0, $sancion_tiempo = 0, $fecha_reserva = null, $id = null) {
        $this->id = $id;
        $this->id_vehiculo = $id_vehiculo;
        $this->id_cliente = $id_cliente;
        $this->fecha_reserva = $fecha_reserva;
        $this->fecha_inicio = $fecha_inicio;
        $this->fecha_fin = $fecha_fin;
        $this->sancion_km = $sancion_km;
        $this->sancion_tiempo = $sancion_tiempo;
        $this->precio_total = $precio_total;
        $this->estado = $estado;
    }

    // --- GETTERS --- //
    public function getId() { return $this->id; }
    public function getIdVehiculo() { return $this->id_vehiculo; }
    public function getIdCliente() { return $this->id_cliente; }
    public function getFechaReserva() { return $this->fecha_reserva; }
    public function getFechaInicio() { return $this->fecha_inicio; }
    public function getFechaFin() { return $this->fecha_fin; }
    public function getSancionKm() { return $this->sancion_km; }
    public function getSancionTiempo() { return $this->sancion_tiempo; }
    public function getPrecioTotal() { return $this->precio_total; }
    public function getEstado() { return $this->estado; }

    // Método para obtener el objeto Vehiculo de esta reserva
    public function getVehiculo() {
        if ($this->vehiculoAsociado == null) {
            $this->vehiculoAsociado = Vehiculo::getById($this->id_vehiculo);
        }
        return $this->vehiculoAsociado;
    }

    // --- MÉTODOS DE BASE DE DATOS --- //

    // Meto la reserva nueva en la base de datos
    public function insert() {
        $conexion = DrivoDB::connectDB();
        $insercion = "INSERT INTO reservas (id_vehiculo, id_cliente, fecha_inicio, fecha_fin, precio_total, estado) 
                      VALUES (:id_vehiculo, :id_cliente, :fecha_inicio, :fecha_fin, :precio_total, :estado)";
        
        $stmt = $conexion->prepare($insercion);
        $stmt->bindParam(':id_vehiculo', $this->id_vehiculo);
        $stmt->bindParam(':id_cliente', $this->id_cliente);
        $stmt->bindParam(':fecha_inicio', $this->fecha_inicio);
        $stmt->bindParam(':fecha_fin', $this->fecha_fin);
        $stmt->bindParam(':precio_total', $this->precio_total);
        $stmt->bindParam(':estado', $this->estado);

        if ($stmt->execute()) {
            $this->id = $conexion->lastInsertId();
            return true;
        }
        return false;
    }

    // Obtengo todas las reservas de un cliente para mostrarlas en su perfil
    public static function getReservasByCliente($id_cliente) {
        $conexion = DrivoDB::connectDB();
        // Ordeno por fecha para que salgan las mas proximas primero
        $consulta = "SELECT * FROM reservas WHERE id_cliente = :id_cliente ORDER BY fecha_inicio ASC";
        $stmt = $conexion->prepare($consulta);
        $stmt->bindParam(':id_cliente', $id_cliente);
        $stmt->execute();
        
        $reservas = [];
        while ($registro = $stmt->fetch(PDO::FETCH_OBJ)) {
            $reservas[] = new Reserva(
                $registro->id_vehiculo, $registro->id_cliente, $registro->fecha_inicio, 
                $registro->fecha_fin, $registro->precio_total, $registro->estado,
                $registro->sancion_km, $registro->sancion_tiempo, $registro->fecha_reserva, $registro->id
            );
        }
        return $reservas;
    }

    // Saco las reservas activas de un coche para comprobar si las fechas estan libres
    public static function getReservasActivasByVehiculo($id_vehiculo) {
        $conexion = DrivoDB::connectDB();
        // Solo las que no esten canceladas y que todavia no hayan pasado
        $consulta = "SELECT fecha_inicio, fecha_fin FROM reservas 
                     WHERE id_vehiculo = :id_vehiculo 
                     AND estado NOT IN ('Cancelada', 'Completada') 
                     AND fecha_fin >= CURDATE()";
        $stmt = $conexion->prepare($consulta);
        $stmt->bindParam(':id_vehiculo', $id_vehiculo);
        $stmt->execute();
        
        $fechas = [];
        while ($registro = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $fechas[] = [
                'inicio' => $registro['fecha_inicio'],
                'fin' => $registro['fecha_fin']
            ];
        }
        return $fechas;
    }

    // Cancelo una reserva y cambio su estado
    public static function cancelar($id_reserva) {
        $conexion = DrivoDB::connectDB();
        
        // Primero busco la reserva para saber de que coche es
        $consultaSelect = "SELECT id_vehiculo FROM reservas WHERE id = :id";
        $stmtSelect = $conexion->prepare($consultaSelect);
        $stmtSelect->bindParam(':id', $id_reserva);
        $stmtSelect->execute();
        $registro = $stmtSelect->fetch(PDO::FETCH_OBJ);

        if ($registro) {
            $id_vehiculo = $registro->id_vehiculo;

            // Actualizamos la reserva
            $consultaUpdate = "UPDATE reservas SET estado = 'Cancelada' WHERE id = :id";
            $stmtUpdate = $conexion->prepare($consultaUpdate);
            $stmtUpdate->bindParam(':id', $id_reserva);
            
            if ($stmtUpdate->execute()) {
                return true;
            }
        }
        return false;
    }

    // Aplica o actualiza la sanción de una reserva (para el panel admin)
    public static function updateSancion($id_reserva, $sancion_km, $sancion_tiempo) {
        $conexion = DrivoDB::connectDB();
        $consulta = "UPDATE reservas SET sancion_km = :sancion_km, sancion_tiempo = :sancion_tiempo WHERE id = :id";
        $stmt = $conexion->prepare($consulta);
        $stmt->bindParam(':sancion_km', $sancion_km);
        $stmt->bindParam(':sancion_tiempo', $sancion_tiempo);
        $stmt->bindParam(':id', $id_reserva);
        return $stmt->execute();
    }

    // Devuelve TODAS las reservas con datos del cliente y del vehículo (para admin)
    public static function getTodasReservas() {
        $conexion = DrivoDB::connectDB();
        $consulta = "SELECT r.*, c.nombre, c.apellidos, c.email, f.marca, f.modelo
                     FROM reservas r
                     JOIN clientes c ON r.id_cliente = c.id
                     JOIN flota f ON r.id_vehiculo = f.id
                     ORDER BY r.fecha_reserva DESC";
        return $conexion->query($consulta)->fetchAll(PDO::FETCH_OBJ);
    }

    // Cambia el estado de una reserva (para admin)
    public static function updateEstado($id_reserva, $nuevoEstado) {
        $conexion = DrivoDB::connectDB();
        $stmt = $conexion->prepare("UPDATE reservas SET estado = :estado WHERE id = :id");
        $stmt->bindParam(':estado', $nuevoEstado);
        $stmt->bindParam(':id', $id_reserva);
        return $stmt->execute();
    }

    // Actualiza fechas y precio total de una reserva existente
public static function actualizarFechasYPrecio($id, $fecha_inicio, $fecha_fin, $precio_total) {
    $conexion = DrivoDB::connectDB();
    $consulta = "UPDATE reservas 
                 SET fecha_inicio = :fecha_inicio, 
                     fecha_fin = :fecha_fin, 
                     precio_total = :precio_total 
                 WHERE id = :id";
    
    $stmt = $conexion->prepare($consulta);
    $stmt->bindParam(':fecha_inicio', $fecha_inicio);
    $stmt->bindParam(':fecha_fin', $fecha_fin);
    $stmt->bindParam(':precio_total', $precio_total);
    $stmt->bindParam(':id', $id);
    
    return $stmt->execute();
}

}
?>
