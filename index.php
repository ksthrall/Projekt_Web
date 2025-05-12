<?php
require 'functions.php';

// Allow anyone to access this page
$categories = getCategories();
$parfumes = getParfumes();
$isAdmin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';

if (isset($_GET['delete_id'])) {
    $perfumeId = $_GET['delete_id'];
    deletePerfume($perfumeId);
    header("Location: index.php"); // Redirect to refresh the page after deletion
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
  <link rel="stylesheet" href="frontend.css">
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
  <header class="header">
    <div class="logo">Jeremy Fragrance</div>
    <div class="auth-links">
      <?php if (isset($_SESSION['user_id'])): ?>
        <span>Mirësevini!</span>
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
      <button type="submit">Kërko</button>
    </form>
    <?php foreach ($categories as $cat): ?>
      <a href="?category=<?= $cat['id_kategori'] ?>"><?= htmlspecialchars($cat['emri']) ?></a>
    <?php endforeach; ?>
  </nav>

  <!-- Admin-only buttons and forms -->
  <?php if ($isAdmin): ?>
    <!-- Button to toggle add perfume form -->
    <button id="toggle-add-form-button" onclick="toggleAddForm()">Shto Parfum</button>

    <!-- Add perfume form -->
    <div id="add-perfume-form" style="display: none;">
      <h2>Shto Parfum të Ri</h2>
      <form method="POST" action="add-perfume.php" enctype="multipart/form-data">
        <div>
          <label for="emri">Emri i Parfumit:</label>
          <input type="text" name="emri" id="emri" required>
        </div>
        <div>
          <label for="marka">Marka:</label>
          <input type="text" name="marka" id="marka" required>
        </div>
        <div>
          <label for="kategori">Kategoria:</label>
          <select name="id_kategori" id="kategori" required>
            <?php foreach ($categories as $cat): ?>
              <option value="<?= $cat['id_kategori'] ?>"><?= htmlspecialchars($cat['emri']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div>
          <label for="cmimi">Çmimi (€):</label>
          <input type="number" name="cmimi" id="cmimi" step="0.01" required>
        </div>
        <div>
          <label for="img">Përzgjedh imazhin:</label>
          <input type="file" name="img" id="img" accept="image/*">
        </div>
        <button type="submit">Ruaj Parfumin</button>
      </form>
    </div>

    <!-- Button to toggle edit perfume form -->
    <button id="toggle-edit-form-button" onclick="toggleEditForm()">Edito Parfum</button>

    <!-- Edit perfume form -->
    <div id="edit-perfume-form" style="display: none;">
      <h2>Edito Parfum</h2>
      <form method="POST" action="edit-perfume.php">
        <div>
          <label for="select_perfume">Përzgjedh Parfum:</label>
          <select name="edit_id" id="select_perfume" onchange="handleEditSelection()">
            <option value="">Përzgjedh një parfum për të edituar</option>
            <?php foreach ($parfumes as $perfume): ?>
              <option value="<?= $perfume['id_produkti'] ?>"><?= htmlspecialchars($perfume['emri']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div>
          <label for="emri">Emri i Parfumit:</label>
          <input type="text" name="emri" id="emri" required>
        </div>

        <div>
          <label for="marka">Marka:</label>
          <input type="text" name="marka" id="marka" required>
        </div>

        <div>
          <label for="id_kategori">Kategoria:</label>
          <select name="id_kategori" id="id_kategori" required>
            <?php foreach ($categories as $cat): ?>
              <option value="<?= $cat['id_kategori'] ?>"><?= htmlspecialchars($cat['emri']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div>
          <label for="cmimi">Çmimi (€):</label>
          <input type="number" name="cmimi" id="cmimi" required>
        </div>

        <div>
          <label for="img">Imazhi:</label>
          <input type="text" name="img" id="img">
        </div>

        <button type="submit">Ruaj Ndryshimet</button>
      </form>
    </div>
  <?php endif; ?>

  <main class="product-list">
    <?php foreach ($parfumes as $p): ?>
      <div class="product-card">
        <img src="foto/<?= htmlspecialchars(empty($p['img']) ? 'placeholder.jpg' : $p['img']) ?>" alt="<?= htmlspecialchars($p['emri']) ?>">
        <h3><?= htmlspecialchars($p['emri']) ?> 
          <?php if ($isAdmin): ?>
            <a href="?delete_id=<?= $p['id_produkti'] ?>" onclick="return confirm('A jeni të sigurt që doni të fshini këtë parfum?');">X</a>
          <?php endif; ?>
        </h3>
        <p><?= htmlspecialchars($p['marka']) ?></p>
        <p><?= number_format($p['cmimi'], 2) ?> €</p>
      </div>
    <?php endforeach; ?>
  </main>
</body>
</html>
