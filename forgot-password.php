<?php
require 'functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    if ($email && sendPasswordReset($email)) {
        echo "Një email me udhëzime është dërguar.";
    } else {
        echo "Email-i nuk u gjet ose ndodhi gabim.";
    }
}
