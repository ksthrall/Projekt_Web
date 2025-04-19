<?php
require_once 'config.php';

/**
 * Regjistron një user të ri
 */
function registerUser($username, $email, $password) {
    global $pdo;
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (username,email,password) VALUES (?,?,?)");
    return $stmt->execute([$username, $email, $hash]);
}

/**
 * Bën login: kthen array(user) nëse suksesshëm, false në të kundërt
 */
function loginUser($identifier, $password) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? OR username = ?");
    $stmt->execute([$identifier, $identifier]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user && password_verify($password, $user['password'])) {
        return $user;
    }
    return false;
}

/**
 * Dërgon email për rikuperim fjalëkalimi
 */
function sendPasswordReset($email) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if (!$row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        return false;
    }
    $token = bin2hex(random_bytes(32));
    $expires = date('Y-m-d H:i:s', time() + 3600); // skadon pas 1 ore
    $upd = $pdo->prepare("UPDATE users SET reset_token = ?, reset_expires = ? WHERE id = ?");
    $upd->execute([$token, $expires, $row['id']]);

    $link = "http://tuadomen.com/reset-password.php?token=$token";
    $subject = "Rikuperim Fjalëkalimi";
    $message = "Për të rikuperuar fjalëkalimin, klikoni: $link";
    // siguroje që sende-mail funksionon, ose përdor PHPMailer
    return mail($email, $subject, $message);
}

/**
 * Verifikon token-in dhe kthen ID-në e user-it, ose false
 */
function verifyToken($token) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT id FROM users WHERE reset_token = ? AND reset_expires > NOW()");
    $stmt->execute([$token]);
    return $stmt->fetchColumn();
}

/**
 * Vendos fjalëkalim të ri
 */
function resetPassword($userId, $newPassword) {
    global $pdo;
    $hash = password_hash($newPassword, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_expires = NULL WHERE id = ?");
    return $stmt->execute([$hash, $userId]);
}

/**
 * Nxjerr të dhënat e user-it sipas ID
 */
function getUserById($id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT id,username,email,role FROM users WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
