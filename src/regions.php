<?php
require_once __DIR__ . '/../config/database.php';
$res_countries = pg_query($conn, "SELECT id, name FROM countries ORDER BY name");
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $country_id = intval($_POST['country_id'] ?? 0);
    $name = trim($_POST['name'] ?? '');
    $abbrev = trim($_POST['abbrev'] ?? '');
    $code = trim($_POST['code'] ?? '');
    if ($country_id > 0 && $name !== '') {
        $sql = "INSERT INTO regions (country_id, name, abbrev, code) VALUES ($1,$2,$3,$4) ON CONFLICT (country_id,name) DO NOTHING";
        $res = pg_query_params($conn, $sql, array($country_id,$name,$abbrev,$code));
        if ($res === false) $error = pg_last_error($conn);
        else header("Location: regions.php");
    } else $error = "Seleccione país y nombre.";
}
$list = pg_query($conn, "SELECT r.id, r.name, r.abbrev, r.code, c.name as country FROM regions r JOIN countries c ON r.country_id = c.id ORDER BY c.name, r.name");
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Regions</title>
<link rel="stylesheet" href="assets/style.css">
</head>
<body>
  <div class="container">
    <div class="header"><div class="brand"><h1>Regions</h1></div><div class="nav"><a href="countries.php">Countries</a> | <a href="index.html">Home</a></div></div>
    <div class="grid">
      <div class="card">
        <h3>Crear Region</h3>
        <?php if(!empty($error)) echo '<div style="color:#b91c1c;margin-bottom:8px">'.htmlspecialchars($error).'</div>'; ?>
        <form method="post" action="regions.php">
          <div class="form-row"><label>Country</label>
            <select name="country_id" required>
              <option value="">-- Seleccione país --</option>
              <?php pg_result_seek($res_countries,0); while($c = pg_fetch_assoc($res_countries)): ?>
                <option value="<?=htmlspecialchars($c['id'])?>"><?=htmlspecialchars($c['name'])?></option>
              <?php endwhile; ?>
            </select>
          </div>
          <div class="form-row"><label>Name</label><input name="name" required></div>
          <div class="form-row"><label>Abbrev</label><input name="abbrev"></div>
          <div class="form-row"><label>Code</label><input name="code"></div>
          <div class="form-actions"><button class="btn" type="submit">Create</button></div>
        </form>
      </div>
      <div class="card">
        <h3>Listado</h3>
        <div class="list"><ul><?php while($row = pg_fetch_assoc($list)): ?>
          <li><?=htmlspecialchars($row['country'].' / '.$row['name'].' ('.$row['abbrev'].' - '.$row['code'].')')?></li>
        <?php endwhile; ?></ul></div>
      </div>
    </div>
    <div class="footer">Regions vinculadas a Countries</div>
  </div>
</body>
</html>
