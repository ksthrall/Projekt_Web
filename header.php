<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['user_id'])) {
    return;
}

require_once 'config.php';

$userId = (int) $_SESSION['user_id'];
$stmt   = $pdo->prepare("SELECT role_id FROM Perdorues WHERE id = ?");
$stmt->execute([$userId]);
if ((int)$stmt->fetchColumn() === 1) {
    return;
}

$pdo
  ->prepare("INSERT IGNORE INTO cart (id_perdorues) VALUES (?)")
  ->execute([$userId]);

$stmt = $pdo->prepare("SELECT id_cart, cmimi_cart FROM cart WHERE id_perdorues = ?");
$stmt->execute([$userId]);
$cartRow = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
$cartId  = $cartRow['id_cart']   ?? null;
$total   = $cartRow['cmimi_cart'] ?? 0.00;

$count = 0;
if ($cartId) {
    $stmt2 = $pdo->prepare("SELECT SUM(sasia) FROM cart_items WHERE id_cart = ?");
    $stmt2->execute([$cartId]);
    $count = (int)$stmt2->fetchColumn();
}

$items = [];
if ($cartId) {
    $stmt3 = $pdo->prepare("
        SELECT ci.sasia, p.emri, p.cmimi
          FROM cart_items ci
          JOIN Produkti p ON ci.id_produkti = p.id_produkti
         WHERE ci.id_cart = ?
         LIMIT 5
    ");
    $stmt3->execute([$cartId]);
    $items = $stmt3->fetchAll(PDO::FETCH_ASSOC);
}
?>

<style>
  #cart-icon {
    position: fixed;
    top: 20px;
    right: 20px;
    font-size: 1.5rem;
    cursor: pointer;
    z-index: 1000;
  }
  #cart-panel {
    display: none;
    position: fixed;
    top: 60px;
    right: 20px;
    width: 300px;
    max-height: 400px;
    overflow-y: auto;
    background: #fff;
    border: 1px solid #ccc;
    box-shadow: 0 2px 8px rgba(0,0,0,0.2);
    padding: 10px;
    border-radius: 4px;
    z-index: 1000;
  }
  .cart-widget { font-size: 0.9rem; }
  .cart-widget ul { list-style: none; padding: 0; margin: 0; }
  .cart-widget li { padding: 4px 0; border-bottom: 1px solid #eee; }
</style>

<div id="cart-icon">🛒 (<?= $count ?>)</div>
<div id="cart-panel">
  <div class="cart-widget">
    <?php if ($count === 0): ?>
      <p>Shporta juaj është bosh.</p>
    <?php else: ?>
      <ul>
        <?php foreach ($items as $it): 
          $sub = $it['sasia'] * $it['cmimi'];
        ?>
          <li>
            <?= htmlspecialchars($it['emri']) ?>
            × <?= $it['sasia'] ?>
            = <?= number_format($sub,2) ?> €
          </li>
        <?php endforeach; ?>
      </ul>
      <?php if (count($items) === 5): ?>
        <p>…dhe më tepër</p>
      <?php endif; ?>
      <p><strong>Totali: <?= number_format($total,2) ?> €</strong></p>
      <p><a href="cart.php">Shiko shportën →</a></p>
    <?php endif; ?>
  </div>
</div>

<script>
  document.getElementById('cart-icon').addEventListener('click', function(){
    const panel = document.getElementById('cart-panel');
    panel.style.display = panel.style.display === 'block' ? 'none' : 'block';
  });
  document.addEventListener('click', function(e){
    const panel = document.getElementById('cart-panel'),
          icon  = document.getElementById('cart-icon');
    if (!panel.contains(e.target) && !icon.contains(e.target)) {
      panel.style.display = 'none';
    }
  });
</script>