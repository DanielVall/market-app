<?php
require_once __DIR__ . '/../config/database.php';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $f = trim($_POST['fname'] ?? '');
    $l = trim($_POST['lname'] ?? '');
    $m = trim($_POST['mnumber'] ?? '');
    $idn = trim($_POST['idnumber'] ?? '');
    $e = trim($_POST['email'] ?? '');
    $pw = $_POST['passwd'] ?? '';
    $city_birth = $_POST['city_birth'] !== '' ? intval($_POST['city_birth']) : null;
    $city_issue = $_POST['city_issue'] !== '' ? intval($_POST['city_issue']) : null;

    if (!$f || !$l || !$m || !$idn || !$e || !$pw) $error = 'Completa los campos obligatorios';
    else {
        $check = pg_query_params($conn, "SELECT id FROM users WHERE email=$1 OR ide_number=$2 LIMIT 1", array($e,$idn));
        if ($check && pg_num_rows($check) > 0) $error = 'Usuario ya existe';
        else {
            $hash = password_hash($pw, PASSWORD_DEFAULT);
            $sql = "INSERT INTO users (firstname, lastname, mobile_number, ide_number, email, password, city_birth_id, city_issue_id) VALUES ($1,$2,$3,$4,$5,$6,$7,$8)";
            $res = pg_query_params($conn, $sql, array($f,$l,$m,$idn,$e,$hash,$city_birth,$city_issue));
            if ($res === false) $error = pg_last_error($conn);
            else { echo "<script>alert('Registro exitoso. Inicia sesión'); window.location='signin.php';</script>"; exit; }
        }
    }
}
$cities = pg_query($conn, "SELECT ci.id, ci.name as city_name, r.name as region_name, c.name as country_name FROM cities ci JOIN regions r ON ci.region_id = r.id JOIN countries c ON r.country_id = c.id ORDER BY c.name, r.name, ci.name");
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Sign up</title>
<link rel="stylesheet" href="assets/style.css">
</head>
<body>
  <div class="container">
    <div class="header"><div class="brand"><h1>Register</h1></div><div class="nav"><a href="signin.php">Sign in</a> | <a href="index.html">Home</a></div></div>
    <div class="card" style="max-width:700px;margin:0 auto">
      <h3>Crear cuenta</h3>
      <?php if(!empty($error)) echo '<div style="color:#b91c1c;margin-bottom:8px">'.htmlspecialchars($error).'</div>'; ?>
      <form method="post" action="signup.php">
        <div class="form-row"><label>Firstname</label><input name="fname" required></div>
        <div class="form-row"><label>Lastname</label><input name="lname" required></div>
        <div class="form-row"><label>Mobile number</label><input name="mnumber" required></div>
        <div class="form-row"><label>Identification number</label><input name="idnumber" required></div>
        <div class="form-row"><label>Email</label><input type="email" name="email" required></div>
        <div class="form-row"><label>Password</label><input type="password" name="passwd" required></div>
        <div class="form-row"><label>City birth (opcional)</label>
          <select name="city_birth"><option value="">-- none --</option><?php pg_result_seek($cities,0); while($r = pg_fetch_assoc($cities)): ?>
            <option value="<?=htmlspecialchars($r['id'])?>"><?=htmlspecialchars($r['country_name'].' / '.$r['region_name'].' / '.$r['city_name'])?></option>
          <?php endwhile; ?></select></div>
        <?php pg_result_seek($cities,0); ?>
        <div class="form-row"><label>City issue (opcional)</label>
          <select name="city_issue"><option value="">-- none --</option><?php while($r = pg_fetch_assoc($cities)): ?>
            <option value="<?=htmlspecialchars($r['id'])?>"><?=htmlspecialchars($r['country_name'].' / '.$r['region_name'].' / '.$r['city_name'])?></option>
          <?php endwhile; ?></select></div>
        <div class="form-actions"><button class="btn" type="submit">Register</button></div>
      </form>
    </div>
    <div class="footer">Cuenta segura (passwords hashed)</div>
  </div>
</body>
</html>
