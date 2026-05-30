<?php
require_once 'drivoDB.php';

class Valoracion {
    private $id_cliente;
    private $id_vehiculo;
    private $puntuacion;
    private $comentario;
    private $fecha_valoracion;

    public function __construct($id_cliente, $id_vehiculo, $puntuacion, $comentario, $fecha_valoracion = null) {
        $this->id_cliente = $id_cliente;
        $this->id_vehiculo = $id_vehiculo;
        $this->puntuacion = $puntuacion;
        $this->comentario = $comentario;
        $this->fecha_valoracion = $fecha_valoracion;
    }

    public function getIdCliente() { return $this->id_cliente; }
    public function getIdVehiculo() { return $this->id_vehiculo; }
    public function getPuntuacion() { return $this->puntuacion; }
    public function getComentario() { return $this->comentario; }
    public function getFechaValoracion() { return $this->fecha_valoracion; }



    public static function puedeValorar($id_cliente, $id_vehiculo) {
        $conexion = DrivoDB::connectDB();
        
        //  Verificar si tiene una reserva completada de ese vehículo
        $consultaReserva = "SELECT COUNT(*) FROM reservas 
                            WHERE id_cliente = :id_cliente 
                            AND id_vehiculo = :id_vehiculo 
                            AND estado = 'Finalizada'";
        $stmt1 = $conexion->prepare($consultaReserva);
        $stmt1->execute([
            ':id_cliente' => $id_cliente,
            ':id_vehiculo' => $id_vehiculo
        ]);
        
        if ($stmt1->fetchColumn() == 0) {
            return false; // No ha completado ningún alquiler con este coche
        }

        //  Verificar si ya existe una valoración de este cliente para este coche
        $consultaValoracion = "SELECT COUNT(*) FROM valoraciones 
                               WHERE id_cliente = :id_cliente 
                               AND id_vehiculo = :id_vehiculo";
        $stmt2 = $conexion->prepare($consultaValoracion);
        $stmt2->execute([
            ':id_cliente' => $id_cliente,
            ':id_vehiculo' => $id_vehiculo
        ]);

        if ($stmt2->fetchColumn() > 0) {
            return false; // Ya ha valorado este coche antes
        }

        return true; // Cumple todas las condiciones
    }


    public static function getMediaPuntuacion($id_vehiculo) {
        $conexion = DrivoDB::connectDB();
        $consulta = "SELECT AVG(puntuacion) as media FROM valoraciones WHERE id_vehiculo = :id_vehiculo";
        $stmt = $conexion->prepare($consulta);
        $stmt->execute([':id_vehiculo' => $id_vehiculo]);
        $registro = $stmt->fetch(PDO::FETCH_OBJ);

        // Si hay media calculada la redondeamos a 1 decimal, si no, devolvemos un texto por defecto
        return $registro->media ? round($registro->media, 1) : "Sin notas";
    }

    public static function getValoracionesByVehiculo($id_vehiculo) {
        $conexion = DrivoDB::connectDB();
        $consulta = "SELECT * FROM valoraciones WHERE id_vehiculo = :id_vehiculo ORDER BY fecha_valoracion DESC";
        $stmt = $conexion->prepare($consulta);
        $stmt->execute([':id_vehiculo' => $id_vehiculo]);

        $valoraciones = [];
        while ($registro = $stmt->fetch(PDO::FETCH_OBJ)) {
            $valoraciones[] = new Valoracion(
                $registro->id_cliente,
                $registro->id_vehiculo,
                $registro->puntuacion,
                $registro->comentario,
                $registro->fecha_valoracion
            );
        }
        return $valoraciones;
    }

    public function insert() {
        $conexion = DrivoDB::connectDB();
        $insercion = "INSERT INTO valoraciones (id_cliente, id_vehiculo, puntuacion, comentario) 
                      VALUES (:id_cliente, :id_vehiculo, :puntuacion, :comentario)";
        $stmt = $conexion->prepare($insercion);
        $stmt->bindParam(':id_cliente', $this->id_cliente);
        $stmt->bindParam(':id_vehiculo', $this->id_vehiculo);
        $stmt->bindParam(':puntuacion', $this->puntuacion);
        $stmt->bindParam(':comentario', $this->comentario);
        
        return $stmt->execute();
    }
}
?>