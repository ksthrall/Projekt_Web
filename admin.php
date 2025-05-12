
<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.html');
    exit;
}
?>

<!DOCTYPE html>
<html lang="sq">
<head>
  <meta charset="UTF-8">
  <title>Paneli i Administratorit</title>
  <link rel="stylesheet" href="frontend.css">
</head>
<body>
  <!-- Top navigation bar -->
  <div style="background-color: #f5f5f5; padding: 1rem; display: flex; justify-content: space-between; align-items: center;">
    <h2 style="margin: 0;">Paneli i Administratorit</h2>
    <a href="index.php" class="btn btn-secondary" style="text-decoration: none; padding: 0.5rem 1rem; background-color: #ddd; color: black; border-radius: 5px;">Shko te Faqja Kryesore</a>
  </div>

  <div class="container">
    <p>Mirësevini, administrator!</p>

    <!-- Add content for admin management here -->
    <ul>
      <li><a href="register-admin.html">Shto Administrator të Ri</a></li>
      <li><a href="add-perfume-form.php">Shto Parfum</a></li>
      <li><a href="manage-products.php">Menaxho Produktet</a></li>
      <li><a href="manage-comments.php">Menaxho Komentet</a></li>
      <li><a href="logout.php">Dil</a></li>
    </ul>
  </div>
</body>
</html>
