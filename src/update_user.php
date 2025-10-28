<?php
require_once __DIR__ . '/../config/database.php';

// ================== VALIDAR ID ==================
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID de usuario inválido.");
}
$user_id = intval($_GET['id']);

// ================== CONSULTAR DATOS ACTUALES DEL USUARIO ==================
$sql_user = "
  SELECT id, firstname, lastname, mobile_number, ide_number, address, birthday, email, status,
         city_birth_id, city_issue_id
  FROM users
  WHERE id = $user_id
  LIMIT 1
";
$res_user = pg_query($conn, $sql_user);
if (!$res_user || pg_num_rows($res_user) === 0) {
    die("Usuario no encontrado.");
}
$user = pg_fetch_assoc($res_user);

// ================== CONSULTAR TODAS LAS TABLAS ==================
$countries_res = pg_query($conn, "SELECT id, name FROM countries ORDER BY name");
$regions_res = pg_query($conn, "SELECT id, country_id, name FROM regions ORDER BY name");
$cities_res = pg_query($conn, "SELECT id, region_id, name FROM cities ORDER BY name");

// Convertir a arrays para JS
$countries = pg_fetch_all($countries_res);
$regions = pg_fetch_all($regions_res);
$cities = pg_fetch_all($cities_res);

// Obtener país y región actuales del usuario
function getCountryAndRegion($conn, $city_id) {
    if (!$city_id) return [null, null];
    $sql = "
      SELECT co.id AS country_id, r.id AS region_id
      FROM cities c
      JOIN regions r ON c.region_id = r.id
      JOIN countries co ON r.country_id = co.id
      WHERE c.id = $city_id
      LIMIT 1
    ";
    $res = pg_query($conn, $sql);
    if ($res && pg_num_rows($res) > 0) {
        $data = pg_fetch_assoc($res);
        return [$data['country_id'], $data['region_id']];
    }
    return [null, null];
}

list($country_birth, $region_birth) = getCountryAndRegion($conn, $user['city_birth_id']);
list($country_issue, $region_issue) = getCountryAndRegion($conn, $user['city_issue_id']);

// ================== PROCESAR FORMULARIO ==================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstname = trim($_POST['firstname']);
    $lastname = trim($_POST['lastname']);
    $mobile = trim($_POST['mobile_number']);
    $ide = trim($_POST['ide_number']);
    $address = trim($_POST['address']);
    $birthday = trim($_POST['birthday']);
    $status = isset($_POST['status']) ? 'TRUE' : 'FALSE';
    $city_birth = intval($_POST['city_birth_id']);
    $city_issue = intval($_POST['city_issue_id']);

    $sql_update = "
      UPDATE users SET
        firstname = '$firstname',
        lastname = '$lastname',
        mobile_number = '$mobile',
        ide_number = '$ide',
        address = '$address',
        birthday = '$birthday',
        status = $status,
        city_birth_id = $city_birth,
        city_issue_id = $city_issue,
        updated_at = now()
      WHERE id = $user_id
    ";

    if (pg_query($conn, $sql_update)) {
        echo "<script>alert('✅ Usuario actualizado con éxito'); window.location='list_users.php';</script>";
        exit;
    } else {
        echo "<script>alert('❌ Error al actualizar');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Update User</title>
<link rel="stylesheet" href="assets/style.css">
</head>
<body>
<div class="container">
  <div class="header">
      <div class="brand"><h1>Actualizar Usuario</h1></div>
      <div class="nav"><a href="list_users.php" class="btn">Volver</a></div>
  </div>
  <div class="card">
      <form method="POST">
          <div class="form-row"><label>Firstname</label><input type="text" name="firstname" value="<?= htmlspecialchars($user['firstname']) ?>" required></div>
          <div class="form-row"><label>Lastname</label><input type="text" name="lastname" value="<?= htmlspecialchars($user['lastname']) ?>" required></div>
          <div class="form-row"><label>Mobile Number</label><input type="text" name="mobile_number" value="<?= htmlspecialchars($user['mobile_number']) ?>" required></div>
          <div class="form-row"><label>Identification</label><input type="text" name="ide_number" value="<?= htmlspecialchars($user['ide_number']) ?>"></div>
          <div class="form-row"><label>Address</label><input type="text" name="address" value="<?= htmlspecialchars($user['address']) ?>"></div>
          <div class="form-row"><label>Birthday</label><input type="date" name="birthday" value="<?= htmlspecialchars($user['birthday']) ?>"></div>
          
          <div class="form-row">
              <label>Status</label>
              <input type="checkbox" name="status" <?= $user['status'] ? 'checked' : '' ?>> Activo
          </div>

          <h3>Ciudad de nacimiento</h3>
          <div class="form-row">
              <label>Country</label>
              <select id="country_birth" onchange="filterRegions('country_birth','region_birth','city_birth')" required></select>
          </div>
          <div class="form-row">
              <label>Region</label>
              <select id="region_birth" onchange="filterCities('region_birth','city_birth')" required></select>
          </div>
          <div class="form-row">
              <label>City</label>
              <select name="city_birth_id" id="city_birth" required></select>
          </div>

          <h3>Ciudad de expedición</h3>
          <div class="form-row">
              <label>Country</label>
              <select id="country_issue" onchange="filterRegions('country_issue','region_issue','city_issue')" required></select>
          </div>
          <div class="form-row">
              <label>Region</label>
              <select id="region_issue" onchange="filterCities('region_issue','city_issue')" required></select>
          </div>
          <div class="form-row">
              <label>City</label>
              <select name="city_issue_id" id="city_issue" required></select>
          </div>

          <div class="form-actions">
              <button type="submit" class="btn">Guardar</button>
              <a href="list_users.php" class="btn btn-secondary">Cancelar</a>
          </div>
      </form>
  </div>
</div>

<script>
const countries = <?= json_encode($countries) ?>;
const regions = <?= json_encode($regions) ?>;
const cities = <?= json_encode($cities) ?>;

function populateSelect(selectId, items, valueKey, textKey, selectedValue = null) {
    const select = document.getElementById(selectId);
    select.innerHTML = '<option value="">Seleccione</option>';
    items.forEach(item => {
        const selected = item[valueKey] == selectedValue ? 'selected' : '';
        select.innerHTML += `<option value="${item[valueKey]}" ${selected}>${item[textKey]}</option>`;
    });
}

function filterRegions(countrySelectId, regionSelectId, citySelectId) {
    const countryId = document.getElementById(countrySelectId).value;
    const filteredRegions = regions.filter(r => r.country_id == countryId);
    populateSelect(regionSelectId, filteredRegions, 'id', 'name');
    document.getElementById(citySelectId).innerHTML = '<option value="">Seleccione</option>';
}

function filterCities(regionSelectId, citySelectId) {
    const regionId = document.getElementById(regionSelectId).value;
    const filteredCities = cities.filter(c => c.region_id == regionId);
    populateSelect(citySelectId, filteredCities, 'id', 'name');
}

window.onload = function() {
    populateSelect('country_birth', countries, 'id', 'name', <?= json_encode($country_birth) ?>);
    filterRegions('country_birth', 'region_birth', 'city_birth');
    populateSelect('region_birth', regions.filter(r => r.country_id == <?= json_encode($country_birth) ?>), 'id', 'name', <?= json_encode($region_birth) ?>);
    populateSelect('city_birth', cities.filter(c => c.region_id == <?= json_encode($region_birth) ?>), 'id', 'name', <?= json_encode($user['city_birth_id']) ?>);

    populateSelect('country_issue', countries, 'id', 'name', <?= json_encode($country_issue) ?>);
    filterRegions('country_issue', 'region_issue', 'city_issue');
    populateSelect('region_issue', regions.filter(r => r.country_id == <?= json_encode($country_issue) ?>), 'id', 'name', <?= json_encode($region_issue) ?>);
    populateSelect('city_issue', cities.filter(c => c.region_id == <?= json_encode($region_issue) ?>), 'id', 'name', <?= json_encode($user['city_issue_id']) ?>);
}
</script>
</body>
</html>
