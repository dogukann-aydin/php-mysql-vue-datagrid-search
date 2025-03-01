<?php
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once 'db.php';

// Parametreler
$query = isset($_GET['query']) ? $_GET['query'] : '';
$kategori = isset($_GET['kategori']) ? $_GET['kategori'] : '';
$marka = isset($_GET['marka']) ? $_GET['marka'] : '';
$sortKey = isset($_GET['sortKey']) ? $_GET['sortKey'] : 'id'; // Varsayılan sıralama: id
$sortDirection = isset($_GET['sortDirection']) ? $_GET['sortDirection'] : 'asc'; // Varsayılan sıralama yönü: artan

// SQL sorgusu
$sql = "SELECT * FROM urunler WHERE ad LIKE :query OR etiketler LIKE :query";

if ($kategori) {
    $sql .= " AND kategori = :kategori";  // Kategoriye göre filtrele
}

if ($marka) {
    $sql .= " AND marka = :marka";  // Markaya göre filtrele
}

$sql .= " ORDER BY $sortKey $sortDirection"; // Sıralama ekliyoruz

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':query', "%$query%");

if ($kategori) {
    $stmt->bindValue(':kategori', $kategori);
}

if ($marka) {
    $stmt->bindValue(':marka', $marka);
}

$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($products);
?>
