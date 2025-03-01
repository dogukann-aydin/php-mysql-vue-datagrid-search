<?php
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once 'db.php';

// Markaları al
$sql = "SELECT DISTINCT marka FROM urunler";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$brands = $stmt->fetchAll(PDO::FETCH_ASSOC);

// JSON olarak geri döndür
echo json_encode(array_column($brands, 'marka'));
?>
