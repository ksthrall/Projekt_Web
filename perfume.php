<?php
require 'functions.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "Parfum i pavlefshëm.";
    exit;
}

$perfumeId = (int) $_GET['id'];
$perfume = getPerfumeById($perfumeId);

if (!$perfume) {
    echo "Parfumi nuk u gjet.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="sq">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($perfume['emri']) ?> - Detajet</title>
  <link rel="stylesheet" href="home.css">
</head>
<body>

  <div class="top-bar">
    <a href="index.php" class="logo">Jeremy Fragrance</a>
    <div class="auth-links">
      <?php if (isset($_SESSION['user_id'])): ?>
        <a href="<?= $_SESSION['role'] === 'admin' ? 'admin.php' : 'customer.php' ?>">Profili Im</a>
        <a href="logout.php">Dil</a>
      <?php else: ?>
        <a href="login.html">Kyçu</a>
        <a href="register.html">Regjistrohu</a>
      <?php endif; ?>
    </div>
  </div>

  <div class="container">
    <a href="index.php" class="btn btn-secondary">← Kthehu</a>
    <div class="product-detail">
      <img src="foto/<?= htmlspecialchars($perfume['img']) ?>" alt="<?= htmlspecialchars($perfume['emri']) ?>">
      <h1><?= htmlspecialchars($perfume['emri']) ?></h1>
      <p><strong>Marka:</strong> <?= htmlspecialchars($perfume['marka']) ?></p>
      <p><strong>Çmimi:</strong> <?= number_format($perfume['cmimi'], 2) ?> €</p>
      <p><strong>Kategori:</strong> <?= htmlspecialchars(getCategoryName($perfume['id_kategori'])) ?></p>
      <p><em>(Placeholder për vlerësime, stok, të dhëna të tabelës Perfume_Details)</em></p>
    </div>
  </div>

</body>
</html>
