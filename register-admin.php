<?php
require 'functions.php';
session_start();

// Vetem admin mund ta beje kete
if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.html');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // fushat
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $pw    = $_POST['password'] ?? '';
    $cpw   = $_POST['confirm_password'] ?? '';
    $role  = (int)($_POST['role_id'] ?? 0);

    if (!$email) {
        die("Email i pavlefshëm.");
    }
    if ($pw !== $cpw) {
        die("Fjalëkalimet nuk përputhen.");
    }
    if ($role !== 1) {
        die("Roli i gabuar.");
    }

    if (registerAdmin($email, $pw)) {
        header('Location: admin.php');
        exit;
    } else {
        die("Gabim gjatë krijimit të admin‑it.");
    }
}
