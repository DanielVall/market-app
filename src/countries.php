<?php
require_once __DIR__ . '/../config/database.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $abbrev = trim($_POST['abbrev'] ?? '');
    $code = trim($_POST['code'] ?? '');
    if ($name !== '') {
        $sql = "INSERT INTO countries (name, abbrev, code) VALUES ($1,$2,$3) ON CONFLICT (name) DO NOTHING";
        $res = pg_query_params($conn, $sql, array($name,$abbrev,$code));
        if ($res === false) $error = pg_last_error($conn);
        else header("Location: countries.php");
    } else $error = "El nombre es obligatorio.";
}
$res_countries = pg_query($conn, "SELECT id, name, abbrev, code FROM countries ORDER BY name");
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Countries</title>
<link rel="stylesheet" href="assets/style.css">
</head>
<body>
  <div class="container">
    <div class="header"><div class="brand"><h1>Countries</h1></div><div class="nav"><a href="index.html">Home</a></div></div>
    <div class="grid">
      <div class="card">
        <h3>Crear Country</h3>
        <?php if(!empty($error)) echo '<div style="color:#b91c1c;margin-bottom:8px">'.htmlspecialchars($error).'</div>'; ?>
        <form method="post" action="countries.php">
          <div class="form-row"><label>Name</label><input name="name" required></div>
          <div class="form-row"><label>Abbrev</label><input name="abbrev"></div>
          <div class="form-row"><label>Code</label><input name="code"></div>
          <div class="form-actions"><button class="btn" type="submit">Create</button></div>
        </form>
      </div>
      <div class="card">
        <h3>Listado</h3>
        <div class="list"><ul><?php while($r = pg_fetch_assoc($res_countries)): ?>
          <li><?=htmlspecialchars($r['name'].' ('.$r['abbrev'].' - '.$r['code'].')')?></li>
        <?php endwhile; ?></ul></div>
      </div>
    </div>
    <div class="footer">Countries sincronizados</div>
  </div>
</body>
</html>
