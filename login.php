<?php
require 'functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = trim($_POST['identifier']);
    $pw = $_POST['password'];

    if ($user = loginUser($id, $pw)) {
        $_SESSION['user_id'] = $user['id'];
        header('Location: profile.php');
        exit;
    } else {
        die("Kredencialet janë të pasakta.");
    }
}
