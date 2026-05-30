<?php
require_once 'drivoDB.php';

class Vehiculo {
    private $id;
    private $matricula;
    private $marca;
    private $modelo;
    private $motor;
    private $cambios;
    private $traccion;
    private $llantas;
    private $anio;
    private $precio_dia;
    private $imagen;
    private $disponible;
    private $oferta;

    public function __construct($matricula = "", $marca = "", $modelo = "", $motor = "", $cambios = "", $traccion = "", $llantas = 17, $anio = 0, $precio_dia = 0.0, $imagen = "default.jpg", $disponible = 1, $oferta = 0, $id = null) {
        $this->id = $id;
        $this->matricula = $matricula;
        $this->marca = $marca;
        $this->modelo = $modelo;
        $this->motor = $motor;
        $this->cambios = $cambios;
        $this->traccion = $traccion;
        $this->llantas = $llantas;
        $this->anio = $anio;
        $this->precio_dia = $precio_dia;
        $this->imagen = $imagen;
        $this->disponible = $disponible;
        $this->oferta = $oferta;
    }

    // --- GETTERS BÁSICOS --- //
    public function getId() { return $this->id; }
    public function getMatricula() { return $this->matricula; }
    public function getMarca() { return $this->marca; }
    public function getModelo() { return $this->modelo; }
    public function getMotor() { return $this->motor; }
    public function getCambios() { return $this->cambios; }
    public function getTraccion() { return $this->traccion; }
    public function getLlantas() { return $this->llantas; }
    public function getAnio() { return $this->anio; }
    public function getPrecioDia() { return $this->precio_dia; }
    public function getImagen() { return $this->imagen; }
    public function getDisponible() { return $this->disponible; }
    public function getOferta() { return $this->oferta; }

    // Nombre completo del coche
    public function getNombreCompleto() {
        return $this->marca . ' ' . $this->modelo;
    }

    // --- MÉTODOS DE BASE DE DATOS --- //

    // Saco todos los coches para el admin o para el catalogo completo
    public static function getAll() {
        $conexion = DrivoDB::connectDB();
        $consulta = "SELECT * FROM flota";
        $resultado = $conexion->query($consulta);
        
        $vehiculos = [];
        while ($registro = $resultado->fetch(PDO::FETCH_OBJ)) {
            $vehiculos[] = new Vehiculo(
                $registro->matricula, $registro->marca, $registro->modelo, 
                $registro->motor, $registro->cambios, $registro->traccion, 
                $registro->llantas, $registro->anio, $registro->precio_dia, 
                $registro->imagen, $registro->disponible, $registro->oferta, $registro->id
            );
        }
        return $vehiculos;
    }

    // Solo los que no estan reservados ahora mismo
    public static function getDisponibles() {
        $conexion = DrivoDB::connectDB();
        $consulta = "SELECT * FROM flota WHERE disponible = 1";
        $resultado = $conexion->query($consulta);
        
        $vehiculos = [];
        while ($registro = $resultado->fetch(PDO::FETCH_OBJ)) {
            $vehiculos[] = new Vehiculo(
                $registro->matricula, $registro->marca, $registro->modelo, 
                $registro->motor, $registro->cambios, $registro->traccion, 
                $registro->llantas, $registro->anio, $registro->precio_dia, 
                $registro->imagen, $registro->disponible, $registro->oferta, $registro->id
            );
        }
        return $vehiculos;
    }

    // Los coches en oferta que ademas no esten reservados hoy
    public static function getOfertas() {
        $conexion = DrivoDB::connectDB();
        // Esta consulta filtra los que tienen una reserva activa justo hoy
        $consulta = "SELECT f.* FROM flota f 
                     WHERE f.disponible = 1 
                     AND f.oferta = 1 
                     AND f.id NOT IN (
                        SELECT id_vehiculo FROM reservas 
                        WHERE estado NOT IN ('Cancelada', 'Completada') 
                        AND CURDATE() BETWEEN fecha_inicio AND fecha_fin
                     )";
        $resultado = $conexion->query($consulta);
        
        $vehiculos = [];
        while ($registro = $resultado->fetch(PDO::FETCH_OBJ)) {
            $vehiculos[] = new Vehiculo(
                $registro->matricula, $registro->marca, $registro->modelo, 
                $registro->motor, $registro->cambios, $registro->traccion, 
                $registro->llantas, $registro->anio, $registro->precio_dia, 
                $registro->imagen, $registro->disponible, $registro->oferta, $registro->id
            );
        }
        return $vehiculos;
    }

    // Busco un coche por su id para cargarlo en la ficha
    public static function getById($id) {
        $conexion = DrivoDB::connectDB();
        $consulta = "SELECT * FROM flota WHERE id = :id";
        $stmt = $conexion->prepare($consulta);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        $registro = $stmt->fetch(PDO::FETCH_OBJ);
        
        if ($registro) {
            return new Vehiculo(
                $registro->matricula, $registro->marca, $registro->modelo, 
                $registro->motor, $registro->cambios, $registro->traccion, 
                $registro->llantas, $registro->anio, $registro->precio_dia, 
                $registro->imagen, $registro->disponible, $registro->oferta, $registro->id
            );
        }
        return null;
    }

    // Cambio la disponibilidad del coche cuando sea necesario
    public static function setDisponibilidad($id, $estado) {
        $conexion = DrivoDB::connectDB();
        $consulta = "UPDATE flota SET disponible = :estado WHERE id = :id";
        $stmt = $conexion->prepare($consulta);
        $stmt->bindParam(':estado', $estado);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Alterna el estado de oferta del coche (0 → 1 o 1 → 0)
    public static function toggleOferta($id) {
        $conexion = DrivoDB::connectDB();
        $consulta = "UPDATE flota SET oferta = IF(oferta = 1, 0, 1) WHERE id = :id";
        $stmt = $conexion->prepare($consulta);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Inserta un vehículo nuevo en la flota
    public function insert() {
        $conexion = DrivoDB::connectDB();
        $insercion = "INSERT INTO flota (matricula, marca, modelo, motor, cambios, traccion, llantas, anio, precio_dia, imagen, disponible, oferta)
                      VALUES (:matricula, :marca, :modelo, :motor, :cambios, :traccion, :llantas, :anio, :precio_dia, :imagen, :disponible, :oferta)";
        $stmt = $conexion->prepare($insercion);
        $stmt->bindParam(':matricula', $this->matricula);
        $stmt->bindParam(':marca',     $this->marca);
        $stmt->bindParam(':modelo',    $this->modelo);
        $stmt->bindParam(':motor',     $this->motor);
        $stmt->bindParam(':cambios',   $this->cambios);
        $stmt->bindParam(':traccion',  $this->traccion);
        $stmt->bindParam(':llantas',   $this->llantas);
        $stmt->bindParam(':anio',      $this->anio);
        $stmt->bindParam(':precio_dia',$this->precio_dia);
        $stmt->bindParam(':imagen',    $this->imagen);
        $stmt->bindParam(':disponible',$this->disponible);
        $stmt->bindParam(':oferta',    $this->oferta);
        try {
            $stmt->execute();
            $this->id = $conexion->lastInsertId();
            return true;
        } catch (PDOException $e) {
            error_log("Error al insertar vehículo: " . $e->getMessage());
            return false;
        }
    }

    // Actualiza los datos de un vehículo existente
    public function update() {
        $conexion = DrivoDB::connectDB();
        $actualizacion = "UPDATE flota SET matricula=:matricula, marca=:marca, modelo=:modelo, motor=:motor,
                          cambios=:cambios, traccion=:traccion, llantas=:llantas, anio=:anio,
                          precio_dia=:precio_dia, imagen=:imagen, disponible=:disponible, oferta=:oferta
                          WHERE id=:id";
        $stmt = $conexion->prepare($actualizacion);
        $stmt->bindParam(':matricula', $this->matricula);
        $stmt->bindParam(':marca',     $this->marca);
        $stmt->bindParam(':modelo',    $this->modelo);
        $stmt->bindParam(':motor',     $this->motor);
        $stmt->bindParam(':cambios',   $this->cambios);
        $stmt->bindParam(':traccion',  $this->traccion);
        $stmt->bindParam(':llantas',   $this->llantas);
        $stmt->bindParam(':anio',      $this->anio);
        $stmt->bindParam(':precio_dia',$this->precio_dia);
        $stmt->bindParam(':imagen',    $this->imagen);
        $stmt->bindParam(':disponible',$this->disponible);
        $stmt->bindParam(':oferta',    $this->oferta);
        $stmt->bindParam(':id',        $this->id);
        try {
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error al actualizar vehículo: " . $e->getMessage());
            return false;
        }
    }

    // Elimina un vehículo de la flota (solo si no tiene reservas activas)
    public static function deleteById($id) {
        $conexion = DrivoDB::connectDB();
        $consulta = "DELETE FROM flota WHERE id = :id";
        $stmt = $conexion->prepare($consulta);
        $stmt->bindParam(':id', $id);
        try {
            return $stmt->execute();
        } catch (PDOException $e) {
            // La FK bloqueará el borrado si tiene reservas asociadas
            error_log("Error al eliminar vehículo: " . $e->getMessage());
            return false;
        }
    }
}
?>
