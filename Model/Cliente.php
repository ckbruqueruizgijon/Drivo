<?php
require_once 'drivoDB.php';

class Cliente {
    private $id;
    private $usuario;
    private $passw;
    private $email;
    private $nombre;
    private $apellidos;
    private $rol;
    private $fecha_registro;

    public function __construct($usuario = "", $passw = "", $email = "", $nombre = "", $apellidos = "", $rol = "cliente", $id = null, $fecha_registro = null) {
        $this->id = $id;
        $this->usuario = $usuario;
        $this->passw = $passw;
        $this->email = $email;
        $this->nombre = $nombre;
        $this->apellidos = $apellidos;
        $this->rol = $rol;
        $this->fecha_registro = $fecha_registro;
    }

    // --- GETTERS & SETTERS ---
    public function getId() { return $this->id; }
    public function getUsuario() { return $this->usuario; }
    public function getEmail() { return $this->email; }
    public function getNombre() { return $this->nombre; }
    public function getApellidos() { return $this->apellidos; }
    public function getRol() { return $this->rol; }

    // --- MÉTODOS DE BASE DE DATOS (CRUD y utilidades) --- //

    // Registro de un nuevo cliente en la base de datos
    public function insert() {
        $conexion = DrivoDB::connectDB();
        $insercion = "INSERT INTO clientes (usuario, passw, email, nombre, apellidos, rol) 
                      VALUES (:usuario, :passw, :email, :nombre, :apellidos, :rol)";
        
        $stmt = $conexion->prepare($insercion);
        
        // Hasheo la contraseña antes de guardarla, para que no se vea en texto plano
        $hashPass = password_hash($this->passw, PASSWORD_BCRYPT);

        $stmt->bindParam(':usuario', $this->usuario);
        $stmt->bindParam(':passw', $hashPass);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':nombre', $this->nombre);
        $stmt->bindParam(':apellidos', $this->apellidos);
        $stmt->bindParam(':rol', $this->rol);

        try {
            $stmt->execute();
            $this->id = $conexion->lastInsertId();
            return true;
        } catch (PDOException $e) {
            // Si falla (por ejemplo email repetido) devuelvo false
            error_log("Error al insertar cliente: " . $e->getMessage());
            return false;
        }
    }

    // Compruebo el login con password_verify para comparar el hash
    public static function login($usuario, $password) {
        $conexion = DrivoDB::connectDB();
        $consulta = "SELECT * FROM clientes WHERE usuario = :usuario OR email = :usuario";
        
        $stmt = $conexion->prepare($consulta);
        $stmt->bindParam(':usuario', $usuario);
        $stmt->execute();
        
        $registro = $stmt->fetch(PDO::FETCH_OBJ);
        
        // Si existe el usuario y la clave coincide con el hash devuelvo el objeto
        if ($registro && password_verify($password, $registro->passw)) {
            return new Cliente($registro->usuario, $registro->passw, $registro->email, 
                               $registro->nombre, $registro->apellidos, $registro->rol, 
                               $registro->id, $registro->fecha_registro);
        }
        return false;
    }

    // Busco un cliente por su id, lo uso cuando necesito sus datos
    public static function getClienteById($id) {
        $conexion = DrivoDB::connectDB();
        $consulta = "SELECT * FROM clientes WHERE id = :id";
        $stmt = $conexion->prepare($consulta);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        $registro = $stmt->fetch(PDO::FETCH_OBJ);
        
        if ($registro) {
            return new Cliente($registro->usuario, $registro->passw, $registro->email, 
                               $registro->nombre, $registro->apellidos, $registro->rol, 
                               $registro->id, $registro->fecha_registro);
        }
        return null;
    }

    public function getFechaRegistro() { return $this->fecha_registro; }

    // Devuelve todos los clientes (para el panel admin)
    public static function getAll() {
        $conexion = DrivoDB::connectDB();
        $resultado = $conexion->query("SELECT * FROM clientes ORDER BY fecha_registro DESC");
        $clientes = [];
        while ($registro = $resultado->fetch(PDO::FETCH_OBJ)) {
            $clientes[] = new Cliente($registro->usuario, $registro->passw, $registro->email,
                                      $registro->nombre, $registro->apellidos, $registro->rol,
                                      $registro->id, $registro->fecha_registro);
        }
        return $clientes;
    }

    // Actualiza la contraseña de un cliente a partir de su email
    public static function updatePassword(string $email, string $nuevaPass): bool {
        $conexion  = DrivoDB::connectDB();
        $hashPass  = password_hash($nuevaPass, PASSWORD_BCRYPT);
        $consulta  = "UPDATE clientes SET passw = :passw WHERE email = :email";
        $stmt      = $conexion->prepare($consulta);
        $stmt->bindParam(':passw', $hashPass);
        $stmt->bindParam(':email', $email);
        try {
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log('Error al actualizar contraseña: ' . $e->getMessage());
            return false;
        }
    }
}
?>
