<?php
require_once __DIR__ . '/../config/database.php';
$res_regions = pg_query($conn, "SELECT r.id, r.name as region_name, c.name as country_name FROM regions r JOIN countries c ON r.country_id = c.id ORDER BY c.name, r.name");
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $region_id = intval($_POST['region_id'] ?? 0);
    $name = trim($_POST['name'] ?? '');
    $abbrev = trim($_POST['abbrev'] ?? '');
    $code = trim($_POST['code'] ?? '');
    if ($region_id > 0 && $name !== '') {
        $sql = "INSERT INTO cities (region_id, name, abbrev, code) VALUES ($1,$2,$3,$4) ON CONFLICT (region_id,name) DO NOTHING";
        $res = pg_query_params($conn, $sql, array($region_id,$name,$abbrev,$code));
        if ($res === false) $error = pg_last_error($conn);
        else header("Location: cities.php");
    } else $error = "Seleccione región y nombre.";
}
$list = pg_query($conn, "SELECT ci.id, ci.name, ci.abbrev, ci.code, r.name as region, c.name as country FROM cities ci JOIN regions r ON ci.region_id = r.id JOIN countries c ON r.country_id = c.id ORDER BY c.name, r.name, ci.name");
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Cities</title>
<link rel="stylesheet" href="assets/style.css">
</head>
<body>
  <div class="container">
    <div class="header"><div class="brand"><h1>Cities</h1></div><div class="nav"><a href="regions.php">Regions</a> | <a href="index.html">Home</a></div></div>
    <div class="grid">
      <div class="card">
        <h3>Crear City</h3>
        <?php if(!empty($error)) echo '<div style="color:#b91c1c;margin-bottom:8px">'.htmlspecialchars($error).'</div>'; ?>
        <form method="post" action="cities.php">
          <div class="form-row"><label>Region</label>
            <select name="region_id" required>
              <option value="">-- Seleccione región --</option>
              <?php pg_result_seek($res_regions,0); while($r = pg_fetch_assoc($res_regions)): ?>
                <option value="<?=htmlspecialchars($r['id'])?>"><?=htmlspecialchars($r['country_name'].' / '.$r['region_name'])?></option>
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
        <div class="list"><ul><?php while($ro = pg_fetch_assoc($list)): ?>
          <li><?=htmlspecialchars($ro['country'].' / '.$ro['region'].' / '.$ro['name'].' ('.$ro['abbrev'].' - '.$ro['code'].')')?></li>
        <?php endwhile; ?></ul></div>
      </div>
    </div>
    <div class="footer">Cities vinculadas a Regions</div>
  </div>
</body>
</html>
