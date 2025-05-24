<?php
// remove_from_cart.php
require 'config.php';

// 1) Must be logged in
if (empty($_SESSION['user_id'])) {
    header('Location: login.html');
    exit;
}
$userId = (int) $_SESSION['user_id'];

// 2) Fetch this user’s cart ID
$stmt = $pdo->prepare("SELECT id_cart FROM cart WHERE id_perdorues = ?");
$stmt->execute([$userId]);
$cartId = $stmt->fetchColumn();
if (!$cartId) {
    header('Location: ' . ($_GET['redirect'] ?? 'index.php'));
    exit;
}

// 3) Get the product to remove one of
$prodId = isset($_GET['prodId']) && is_numeric($_GET['prodId'])
    ? (int) $_GET['prodId']
    : null;

if ($prodId) {
    // 4) Fetch current quantity
    $stmt = $pdo->prepare("
      SELECT sasia 
        FROM cart_items 
       WHERE id_cart = ? AND id_produkti = ?
    ");
    $stmt->execute([$cartId, $prodId]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        if ($row['sasia'] > 1) {
            // decrement by one
            $pdo->prepare("
              UPDATE cart_items
                 SET sasia = sasia - 1
               WHERE id_cart = ? AND id_produkti = ?
            ")->execute([$cartId, $prodId]);
        } else {
            // last one → delete the row
            $pdo->prepare("
              DELETE FROM cart_items
               WHERE id_cart = ? AND id_produkti = ?
            ")->execute([$cartId, $prodId]);
        }
    }

    // 5) Recalculate total
    $sum = $pdo->prepare("
      SELECT SUM(ci.sasia * p.cmimi)
        FROM cart_items ci
        JOIN Produkti p ON ci.id_produkti = p.id_produkti
       WHERE ci.id_cart = ?
    ");
    $sum->execute([$cartId]);
    $total = $sum->fetchColumn() ?: 0.00;

    // 6) Store new total
    $pdo->prepare("UPDATE cart SET cmimi_cart = ? WHERE id_cart = ?")
        ->execute([$total, $cartId]);
}

// 7) Redirect back, preserving existing params and adding cart_open=1
$redirect = $_GET['redirect'] ?? 'index.php';
$glue = strpos($redirect, '?') !== false ? '&' : '?';
header("Location: {$redirect}{$glue}cart_open=1");
exit;