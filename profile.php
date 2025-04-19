<?php
require 'functions.php';
session_start();

if (empty($_SESSION['user_id'])) {
    header('Location: login.html');
    exit;
}
$user = getUserById($_SESSION['user_id']);
$template = file_get_contents(__DIR__ . '/profile.html');
$search  = ['{{username}}', '{{email}}', '{{role}}'];
$replace = [
    htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8'),
    htmlspecialchars($user['email'],    ENT_QUOTES, 'UTF-8'),
    htmlspecialchars($user['role'],     ENT_QUOTES, 'UTF-8'),
];
$output = str_replace($search, $replace, $template);

echo $output;
