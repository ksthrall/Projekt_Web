<?php
require 'functions.php';  // Include your database connection

// Fetch category parameter from URL if it's passed (for filtering products)
$category = $_GET['category'] ?? '';  // Get the 'category' from the URL, default to empty if not set

// Start building the query to fetch products
$query = "SELECT p.id_produkti, p.emri, p.marka, p.cmimi, k.emri AS kategoria
          FROM Produkti p
          JOIN Kategoria k ON p.id_kategori = k.id_kategori";  // JOIN Kategoria to get category name

// If a category is provided, filter by it
if ($category) {
    $query .= " WHERE k.emri = :category";
}

$stmt = $pdo->prepare($query);

// Bind the category parameter if it's passed in the URL
if ($category) {
    $stmt->bindParam(':category', $category, PDO::PARAM_STR);
}

$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);  // Fetch all products that match the query
?>
