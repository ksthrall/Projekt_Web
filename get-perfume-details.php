<?php
require 'functions.php'; // Your database connection and helper functions

if (isset($_GET['id'])) {
    $perfumeId = $_GET['id'];

    // Prepare the query to fetch the perfume details by ID
    $stmt = $pdo->prepare("SELECT * FROM Produkti WHERE id_produkti = :id_produkti");
    $stmt->bindParam(':id_produkti', $perfumeId, PDO::PARAM_INT);
    $stmt->execute();

    $perfume = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if perfume exists
    if ($perfume) {
        echo json_encode($perfume); // Return the details as JSON
    } else {
        echo json_encode(["error" => "Perfume not found"]);
    }
} else {
    echo json_encode(["error" => "No ID provided"]);
}
?>
