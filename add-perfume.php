<?php
require 'functions.php';
session_start();


if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("Jo e autorizuar.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $emri = $_POST['emri'];
    $marka = $_POST['marka'];
    $kategori = $_POST['id_kategori'];
    $cmimi = $_POST['cmimi'];


    if (isset($_FILES['img']) && $_FILES['img']['error'] === UPLOAD_ERR_OK) {
        $img_tmp = $_FILES['img']['tmp_name'];
        $img_name = basename($_FILES['img']['name']);
        $img_path = 'foto/' . $img_name; 

        if (move_uploaded_file($img_tmp, $img_path)) {

            $stmt = $pdo->prepare("INSERT INTO Produkti (emri, marka, id_kategori, cmimi, img) VALUES (:emri, :marka, :id_kategori, :cmimi, :img)");
            $stmt->bindParam(':emri', $emri, PDO::PARAM_STR);
            $stmt->bindParam(':marka', $marka, PDO::PARAM_STR);
            $stmt->bindParam(':id_kategori', $kategori, PDO::PARAM_INT);
            $stmt->bindParam(':cmimi', $cmimi, PDO::PARAM_STR);
            $stmt->bindParam(':img', $img_name, PDO::PARAM_STR);

            $stmt->execute();
            header("Location: index.php");
            exit;
        } else {
            die("Gabim në ngarkimin e fotos.");
        }
    } else {
        die("Gabim me ngarkimin e fotos.");
    }
}
?>
