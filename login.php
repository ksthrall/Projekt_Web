<?php
require 'functions.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['identifier']);
    $password = $_POST['password'];

    if ($user = loginUser($email, $password)) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role']    = $user['role'];

        if ($user['role'] === 'admin') {
            header('Location: admin.php');
        } else {
            header('Location: customer.php');
        }
        exit;
    } else {
        echo "Kredencialet janë të pasakta. <a href='login.html'>Provo përsëri</a>.";
    }
}
