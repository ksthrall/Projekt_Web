<?php
require 'functions.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    $userId = verifyToken($token);
    if (!$userId) {
        die("Link-u ka skaduar ose është i pavlefshëm.");
    }
    // Shfaq formën për fjalëkalim të ri:
    ?>
    <form method="POST">
      <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
      <label>Fjalëkalim i ri:</label><br>
      <input type="password" name="password" required><br><br>
      <label>Konfirmo fjalëkalimin:</label><br>
      <input type="password" name="confirm_password" required><br><br>
      <button type="submit">Rivendos Fjalëkalimin</button>
    </form>
    <?php
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'];
    $pw = $_POST['password'];
    $cp = $_POST['confirm_password'];
    $userId = verifyToken($token);

    if (!$userId || $pw !== $cp) {
        die("Gabim gjatë rivendosjes.");
    }
    resetPassword($userId, $pw);
    echo "Fjalëkalimi u përditësua me sukses. <a href='login.html'>Hyr këtu</a>";
}
