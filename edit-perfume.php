<?php
require 'functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $emri = $_POST['emri'];
    $marka = $_POST['marka'];
    $kategori = $_POST['id_kategori'];
    $cmimi = $_POST['cmimi'];
    $img = $_POST['img'];
    $id_produkti = $_POST['edit_id'];

    // Update the product
    $stmt = $pdo->prepare("UPDATE Produkti SET emri = ?, marka = ?, id_kategori = ?, cmimi = ?, img = ? WHERE id_produkti = ?");
    $stmt->execute([$emri, $marka, $kategori, $cmimi, $img, $id_produkti]);

    header("Location: index.php");  // Redirect back to the product list
    exit();
}
?>
