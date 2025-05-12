<?php
require 'functions.php';
session_start();

// Check if the user is an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("Jo e autorizuar.");
}

// Handle file upload
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $emri = $_POST['emri'];
    $marka = $_POST['marka'];
    $kategori = $_POST['id_kategori'];
    $cmimi = $_POST['cmimi'];

    // Check if the file was uploaded without errors
    if (isset($_FILES['img']) && $_FILES['img']['error'] === UPLOAD_ERR_OK) {
        $img_tmp = $_FILES['img']['tmp_name'];
        $img_name = basename($_FILES['img']['name']);
        $img_path = 'foto/' . $img_name; // Save the image in 'foto' directory

        // Move the uploaded file to the correct directory
        if (move_uploaded_file($img_tmp, $img_path)) {
            // File uploaded successfully
            // Insert data into the database with the image filename
            $stmt = $pdo->prepare("INSERT INTO Produkti (emri, marka, id_kategori, cmimi, img) VALUES (:emri, :marka, :id_kategori, :cmimi, :img)");
            $stmt->bindParam(':emri', $emri, PDO::PARAM_STR);
            $stmt->bindParam(':marka', $marka, PDO::PARAM_STR);
            $stmt->bindParam(':id_kategori', $kategori, PDO::PARAM_INT);
            $stmt->bindParam(':cmimi', $cmimi, PDO::PARAM_STR);
            $stmt->bindParam(':img', $img_name, PDO::PARAM_STR); // Save the image filename

            $stmt->execute();
            header("Location: index.php"); // Redirect after successful upload
            exit;
        } else {
            die("Gabim në ngarkimin e fotos.");
        }
    } else {
        die("Gabim me ngarkimin e fotos.");
    }
}
?>
