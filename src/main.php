<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: signin.php");
  exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/png" href="src/icons/market_main.png"/>
  <link rel="stylesheet" href="assets/style.css">
  <title>MarketApp - Home</title>
</head>
<body>
  <div class="container" style="text-align:center;">
    <div class="header">
      <div class="brand"><h1>MarketApp Dashboard</h1></div>
      <div class="nav">
        <span style="color:#555;margin-right:12px;">👤 <?= htmlspecialchars($_SESSION['user_name'] ?? 'Usuario') ?></span>
        <a href="logout.php" class="btn">Logout</a>
      </div>
    </div>

    <div class="card" style="max-width:600px;margin:0 auto;">
      <h3>Bienvenido, <?= htmlspecialchars($_SESSION['user_name'] ?? 'Usuario') ?> 👋</h3>
      <p>Selecciona una acción para continuar:</p>
      <div class="form-actions" style="justify-content:center;">
        <a href="list_users.php" class="btn secondary">👥 Ver usuarios</a>
        <a href="signup.php" class="btn">➕ Registrar nuevo usuario</a>
      </div>
    </div>

    <div class="footer">Sesión iniciada correctamente</div>
  </div>
</body>
</html>
