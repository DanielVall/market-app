<?php
require_once __DIR__ . '/../config/database.php';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $pw = $_POST['passwd'] ?? '';
    if (!$email || !$pw) $error = 'Completa email y password';
    else {
        $res = pg_query_params($conn, "SELECT id, password FROM users WHERE email = $1 LIMIT 1", array($email));
        if ($res && pg_num_rows($res) == 1) {
            $row = pg_fetch_assoc($res);
            if (password_verify($pw, $row['password'])) {
                // logged in -> redirect main (no session implemented for simplicity)
                header("Location: main.php");
                exit;
            } else $error = 'Credenciales inválidas';
        } else $error = 'Usuario no encontrado';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Sign in</title>
<link rel="stylesheet" href="assets/style.css">
</head>
<body>
  <div class="container">
    <div class="header"><div class="brand"><h1>Sign in</h1></div><div class="nav"><a href="signup.php">Sign up</a> | <a href="index.html">Home</a></div></div>
    <div class="card" style="max-width:500px;margin:0 auto">
      <?php if(!empty($error)) echo '<div style="color:#b91c1c;margin-bottom:8px">'.htmlspecialchars($error).'</div>'; ?>
      <form method="post" action="signin.php">
        <div class="form-row"><label>Email</label><input name="email" type="email" required></div>
        <div class="form-row"><label>Password</label><input name="passwd" type="password" required></div>
        <div class="form-actions"><button class="btn" type="submit">Login</button></div>
      </form>
    </div>
    <div class="footer">Inicia sesión con tu cuenta</div>
  </div>
</body>
</html>
