<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once __DIR__ . '/../modelos/Producto.php';

$producto = new Producto();
$op = $_GET['op'] ?? '';

switch ($op) {

    case 'listar':
        echo json_encode($producto->listar());
        break;

    case 'obtener':
        $id     = intval($_GET['id'] ?? 0);
        $result = $producto->obtener($id);
        echo json_encode($result ?: ["error" => "No encontrado"]);
        break;

    case 'guardar':
        $nombre = trim($_POST['nombre'] ?? '');
        $precio = floatval($_POST['precio'] ?? 0);
        $estado = intval($_POST['estado'] ?? 1);

        if ($nombre === '' || $precio <= 0) {
            echo json_encode(["status" => false, "message" => "Datos inválidos"]);
            break;
        }
        $result = $producto->insertar($nombre, $precio, $estado);
        echo json_encode([
            "status"  => $result,
            "message" => $result ? "Producto guardado" : "Error al guardar"
        ]);
        break;

    case 'actualizar':
        $id     = intval($_POST['id'] ?? 0);
        $nombre = trim($_POST['nombre'] ?? '');
        $precio = floatval($_POST['precio'] ?? 0);
        $estado = intval($_POST['estado'] ?? 1);

        if ($id <= 0 || $nombre === '' || $precio <= 0) {
            echo json_encode(["status" => false, "message" => "Datos inválidos"]);
            break;
        }
        $result = $producto->actualizar($id, $nombre, $precio, $estado);
        echo json_encode([
            "status"  => $result,
            "message" => $result ? "Producto actualizado" : "Error al actualizar"
        ]);
        break;

    case 'eliminar':
        $id = intval($_POST['id'] ?? 0);
        if ($id <= 0) {
            echo json_encode(["status" => false, "message" => "ID inválido"]);
            break;
        }
        $result = $producto->eliminar($id);
        echo json_encode([
            "status"  => $result,
            "message" => $result ? "Producto eliminado" : "Error al eliminar"
        ]);
        break;

    default:
        echo json_encode(["error" => "Operación no válida"]);
        break;
}
