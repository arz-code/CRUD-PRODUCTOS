<?php
require_once __DIR__ . '/../conexion/Conexion.php';

class Producto {
    private $conn;
    private $table = 'producto';

    public function __construct() {
        $db = new Conexion();
        $this->conn = $db->conectar();
    }

    public function listar() {
        $sql  = "SELECT * FROM {$this->table} ORDER BY id DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtener($id) {
        $sql  = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insertar($nombre, $precio, $estado) {
        $sql  = "INSERT INTO {$this->table} (nombre, precio, estado)
                 VALUES (:nombre, :precio, :estado)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':nombre' => $nombre,
            ':precio' => $precio,
            ':estado' => $estado
        ]);
    }

    public function actualizar($id, $nombre, $precio, $estado) {
        $sql  = "UPDATE {$this->table}
                 SET nombre = :nombre, precio = :precio, estado = :estado
                 WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':id'     => $id,
            ':nombre' => $nombre,
            ':precio' => $precio,
            ':estado' => $estado
        ]);
    }

    public function eliminar($id) {
        $sql  = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}
