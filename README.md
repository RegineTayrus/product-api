<?php
/**
 * ByteStore Tech Gadgets API
 * BSCS Midterm Requirement
 */

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// --- DATABASE CONNECTION ---
$host = "localhost";
$db_name = "bytestore_db";
$username = "root";
$password = "";

try {
    $conn = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die(json_encode(["code" => 500, "message" => "DB Error: " . $e->getMessage()]));
}

// --- ROUTING LOGIC ---
$url = isset($_GET['url']) ? rtrim($_GET['url'], '/') : '';
$parts = explode('/', $url);
$method = $_SERVER['REQUEST_METHOD'];
$resource = $parts[0] ?? '';
$id = $parts[1] ?? null;

// Validate if endpoint is '/inventory'
if ($resource !== 'inventory') {
    http_response_code(404);
    echo json_encode(["code" => 404, "message" => "Invalid Endpoint"]);
    exit;
}

switch($method) {
    case 'GET':
        if ($id) {
            $stmt = $conn->prepare("SELECT * FROM gadgets WHERE gadget_id = ?");
            $stmt->execute([$id]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            $stmt = $conn->prepare("SELECT * FROM gadgets");
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        echo json_encode(["code" => 200, "status" => "success", "payload" => $data]);
        break;

    case 'POST':
        $input = json_decode(file_get_contents("php://input"), true);
        if(!empty($input['model_name'])) {
            $sql = "INSERT INTO gadgets (model_name, unit_price, stock_qty) VALUES (?, ?, ?)";
            $conn->prepare($sql)->execute([$input['model_name'], $input['unit_price'], $input['stock_qty']]);
            echo json_encode(["code" => 201, "message" => "New gadget registered"]);
        }
        break;

    case 'PUT':
        if ($id) {
            $input = json_decode(file_get_contents("php://input"), true);
            $sql = "UPDATE gadgets SET model_name=?, unit_price=?, stock_qty=? WHERE gadget_id=?";
            $conn->prepare($sql)->execute([$input['model_name'], $input['unit_price'], $input['stock_qty'], $id]);
            echo json_encode(["code" => 200, "message" => "Gadget info updated"]);
        }
        break;

    case 'DELETE':
        if ($id) {
            $stmt = $conn->prepare("DELETE FROM gadgets WHERE gadget_id = ?");
            $stmt->execute([$id]);
            echo json_encode(["code" => 200, "message" => "Gadget removed"]);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(["message" => "Method Not Allowed"]);
        break;
}
