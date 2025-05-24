<?php
require 'functions.php';


$categories = getCategories();
$parfumes = getParfumes();
$isAdmin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';

if (isset($_GET['delete_id'])) {
    $perfumeId = $_GET['delete_id'];
    deletePerfume($perfumeId);
    header("Location: index.php");
    exit();
}

function deletePerfume($id) {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM Produkti WHERE id_produkti = :id_produkti");
    $stmt->bindParam(':id_produkti', $id, PDO::PARAM_INT);
    $stmt->execute();
}


?>

<!DOCTYPE html>
<html lang="sq">
<head>
  <meta charset="UTF-8">
  <title>Jeremy Fragrance - Dyqani</title>
  <link rel="stylesheet" href="home.css">
  <script>
    function toggleAddForm() {
      var form = document.getElementById('add-perfume-form');
      var button = document.getElementById('toggle-add-form-button');
      if (form.style.display === 'none' || form.style.display === '') {
        form.style.display = 'block';
        button.textContent = 'Fshih Formën';
      } else {
        form.style.display = 'none';
        button.textContent = 'Shto Parfum';
      }
    }

    function toggleEditForm() {
      var form = document.getElementById('edit-perfume-form');
      var button = document.getElementById('toggle-edit-form-button');
      if (form.style.display === 'none' || form.style.display === '') {
        form.style.display = 'block';
        button.textContent = 'Fshih Formën';
      } else {
        form.style.display = 'none';
        button.textContent = 'Edito Parfum';
      }
    }
  </script>
</head>
<body>
<?php include 'header.php'; ?>

  <div class="container">
    <header class="header">
      <div class="logo">Jeremy Fragrance</div>
      <div class="auth-links">
       <?php if (isset($_SESSION['user_id'])): ?>
    <a href="<?= $_SESSION['role'] === 'admin' ? 'admin.php' : 'customer.php' ?>">Profili Im</a>
    <a href="logout.php">Dil</a>
  <?php else: ?>
    <a href="login.html">Hyr</a>
    <a href="register.html">Regjistrohu</a>
  <?php endif; ?>
</div>

    </header>

    <nav class="navbar">
      <form method="GET" class="search-bar">
        <input type="text" name="query" placeholder="Kërko parfume..." value="<?= htmlspecialchars($_GET['query'] ?? '') ?>">
        <button type="submit" class="btn btn-primary">Kërko</button>
      </form>
      <div class="category-links">
        <?php foreach ($categories as $cat): ?>
          <a href="?category=<?= $cat['id_kategori'] ?>"><?= htmlspecialchars($cat['emri']) ?></a>
        <?php endforeach; ?>
      </div>
    </nav>

    <?php if ($isAdmin): ?>
      <div class="admin-controls">
        <button id="toggle-add-form-button" class="btn btn-primary" onclick="toggleAddForm()">Shto Parfum</button>

        <div id="add-perfume-form" class="form-wrapper" style="display: none;">
          <h2>Shto Parfum të Ri</h2>
          <form method="POST" action="add-perfume.php" enctype="multipart/form-data" class="form">
            <div class="form-group">
              <label for="emri">Emri i Parfumit:</label>
              <input type="text" name="emri" id="emri" required>
            </div>
            <div class="form-group">
              <label for="marka">Marka:</label>
              <input type="text" name="marka" id="marka" required>
            </div>
            <div class="form-group">
              <label for="kategori">Kategoria:</label>
              <select name="id_kategori" id="kategori" required>
                <?php foreach ($categories as $cat): ?>
                  <option value="<?= $cat['id_kategori'] ?>"><?= htmlspecialchars($cat['emri']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="form-group">
              <label for="cmimi">Çmimi (€):</label>
              <input type="number" name="cmimi" id="cmimi" step="0.01" required>
            </div>
            <div class="form-group">
              <label for="img">Përzgjedh imazhin:</label>
              <input type="file" name="img" id="img" accept="image/*">
            </div>
            <button type="submit" class="btn btn-primary">Ruaj Parfumin</button>
          </form>
        </div>

        <button id="toggle-edit-form-button" class="btn btn-primary" onclick="toggleEditForm()">Edito Parfum</button>

        <div id="edit-perfume-form" class="form-wrapper" style="display: none;">
          <h2>Edito Parfum</h2>
          <form method="POST" action="edit-perfume.php" class="form">
            <div class="form-group">
              <label for="select_perfume">Përzgjedh Parfum:</label>
              <select name="edit_id" id="select_perfume">
                <option value="">Përzgjedh një parfum për të edituar</option>
                <?php foreach ($parfumes as $perfume): ?>
                  <option value="<?= $perfume['id_produkti'] ?>"><?= htmlspecialchars($perfume['emri']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="form-group">
              <label for="emri">Emri i Parfumit:</label>
              <input type="text" name="emri" id="emri" required>
            </div>
            <div class="form-group">
              <label for="marka">Marka:</label>
              <input type="text" name="marka" id="marka" required>
            </div>
            <div class="form-group">
              <label for="id_kategori">Kategoria:</label>
              <select name="id_kategori" id="id_kategori" required>
                <?php foreach ($categories as $cat): ?>
                  <option value="<?= $cat['id_kategori'] ?>"><?= htmlspecialchars($cat['emri']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="form-group">
              <label for="cmimi">Çmimi (€):</label>
              <input type="number" name="cmimi" id="cmimi" required>
            </div>
            <div class="form-group">
              <label for="img">Imazhi:</label>
              <input type="text" name="img" id="img">
            </div>
            <button type="submit" class="btn btn-primary">Ruaj Ndryshimet</button>
          </form>
        </div>
      </div>
    <?php endif; ?>

    <main class="product-list">
      <?php foreach ($parfumes as $p): ?>
<div class="product-card">
  <a href="perfume.php?id=<?= $p['id_produkti'] ?>">
    <img src="foto/<?= htmlspecialchars(empty($p['img']) ? 'placeholder.jpg' : $p['img']) ?>" alt="<?= htmlspecialchars($p['emri']) ?>">
    <h3><?= htmlspecialchars($p['emri']) ?></h3>
  </a>
  <p><?= htmlspecialchars($p['marka']) ?></p>
  <p><?= number_format($p['cmimi'], 2) ?> €</p>
  <?php if ($isAdmin): ?>
    <a href="?delete_id=<?= $p['id_produkti'] ?>" class="delete-link" onclick="return confirm('A jeni të sigurt që doni të fshini këtë parfum?');">X</a>
  <?php endif; ?>
</div>

      <?php endforeach; ?>
    </main>
  </div>
</body>
</html>
