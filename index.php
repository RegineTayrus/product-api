<?php
header("Content-Type: application/json");

// =======================
// MOCK DATABASE (ARRAY)
// =======================
$products = [
    ["id" => 1, "product" => "Laptop", "price" => 50000],
    ["id" => 2, "product" => "Phone", "price" => 20000]
];

// Get method + URL parsing
$method = $_SERVER['REQUEST_METHOD'];
$uri = explode("/", trim($_SERVER['REQUEST_URI'], "/"));
$id = isset($uri[1]) ? (int)$uri[1] : null;


// =======================
// RESPONSE FUNCTION
// =======================
function response($status, $message, $data = null) {
    echo json_encode([
        "status" => $status,
        "message" => $message,
        "data" => $data
    ]);
    exit;
}


// =======================
// GET ALL PRODUCTS
// Endpoint: /api/products
// =======================
if ($method == "GET" && !isset($id)) {
    response("success", "All products fetched", $products);
}


// =======================
// GET SINGLE PRODUCT
// Endpoint: /api/products/1
// =======================
if ($method == "GET" && isset($id)) {
    foreach ($products as $product) {
        if ($product["id"] == $id) {
            response("success", "Product found", $product);
        }
    }
    response("error", "Product not found");
}


// =======================
// CREATE PRODUCT
// Endpoint: POST /api/products
// =======================
if ($method == "POST") {
    $data = json_decode(file_get_contents("php://input"), true);

    // Validation
    if (empty($data["product"]) || $data["price"] <= 0) {
        response("error", "Invalid product data");
    }

    $newProduct = [
        "id" => count($products) + 1,
        "product" => $data["product"],
        "price" => $data["price"]
    ];

    response("success", "Product created", $newProduct);
}


// =======================
// UPDATE PRODUCT
// Endpoint: PUT /api/products/1
// =======================
if ($method == "PUT" && isset($id)) {
    $data = json_decode(file_get_contents("php://input"), true);

    if (empty($data["product"]) || $data["price"] <= 0) {
        response("error", "Invalid product data");
    }

    $updatedProduct = [
        "id" => $id,
        "product" => $data["product"],
        "price" => $data["price"]
    ];

    response("success", "Product updated", $updatedProduct);
}


// =======================
// DELETE PRODUCT
// Endpoint: DELETE /api/products/1
// =======================
if ($method == "DELETE" && isset($id)) {
    response("success", "Product deleted", ["id" => $id]);
}


// =======================
// DEFAULT RESPONSE
// =======================
response("error", "Method not allowed");
?>
