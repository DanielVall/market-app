<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: signin.php");
  exit;
}

require_once __DIR__ . '/../config/database.php';

// ================== BUSCADOR ==================
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

$sql_users = "
  SELECT u.id, u.firstname, u.lastname, u.email, u.ide_number, u.mobile_number, u.status,
         cb.name AS city_birth, ci.name AS city_issue
  FROM users u
  LEFT JOIN cities cb ON u.city_birth_id = cb.id
  LEFT JOIN cities ci ON u.city_issue_id = ci.id
";

if ($search !== '') {
    $search_sql = pg_escape_string($conn, $search);
    $sql_users .= " WHERE 
        u.firstname ILIKE '%{$search_sql}%' OR
        u.lastname ILIKE '%{$search_sql}%' OR
        u.email ILIKE '%{$search_sql}%' OR
        u.ide_number ILIKE '%{$search_sql}%'
    ";
}

$sql_users .= " ORDER BY u.id ";

$res = pg_query($conn, $sql_users);
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Users</title>
<link rel="stylesheet" href="assets/style.css">
</head>
<body>
  <div class="container">
    <div class="header">
      <div class="brand"><h1>Users</h1></div>
      <div class="nav">
        <span style="color:#555;margin-right:12px;">👤 <?= htmlspecialchars($_SESSION['user_name'] ?? 'Usuario') ?></span>
        <a href="main.php" class="btn secondary">🏠 Main</a>
        <a href="logout.php" class="btn">Logout</a>
      </div>
    </div>

    <div class="card">
      <h3>Buscar usuarios</h3>
      <form method="get" action="list_users.php" style="display:flex;gap:8px;flex-wrap:wrap;">
        <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Buscar por nombre, email o ID" class="input" style="flex:1;min-width:200px;">
        <button type="submit" class="btn">Buscar</button>
        <a href="list_users.php" class="btn secondary">Limpiar</a>
      </form>
    </div>

    <div class="card">
      <h3>Lista de usuarios</h3>
      <table class="table">
        <thead>
          <tr>
            <th>Fullname</th>
            <th>Email</th>
            <th>Ide</th>
            <th>Phone</th>
            <th>Birth</th>
            <th>Issue</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php while($u = pg_fetch_assoc($res)): ?>
            <tr>
              <td><?= htmlspecialchars($u['firstname'].' '.$u['lastname']) ?></td>
              <td><?= htmlspecialchars($u['email']) ?></td>
              <td><?= htmlspecialchars($u['ide_number']) ?></td>
              <td><?= htmlspecialchars($u['mobile_number']) ?></td>
              <td><?= htmlspecialchars($u['city_birth']) ?></td>
              <td><?= htmlspecialchars($u['city_issue']) ?></td>
              <td><?= $u['status'] ? '✅ Active' : '❌ Inactive' ?></td>
              <td>
                <a href="update_user.php?id=<?= $u['id'] ?>" class="btn btn-warning">✏ Edit</a>
                <a href="delete_user.php?id=<?= $u['id'] ?>" class="btn btn-danger" onclick="return confirm('¿Seguro que deseas eliminar este usuario?');">🗑 Delete</a>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>

    <div class="footer">Datos sincronizados con la base de datos</div>
  </div>
</body>
</html>
