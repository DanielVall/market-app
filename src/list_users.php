<?php
require_once __DIR__ . '/../config/database.php';
$sql_users = "
  SELECT u.id, u.firstname, u.lastname, u.email, u.ide_number, u.mobile_number, u.status,
         cb.name AS city_birth, ci.name AS city_issue
  FROM users u
  LEFT JOIN cities cb ON u.city_birth_id = cb.id
  LEFT JOIN cities ci ON u.city_issue_id = ci.id
  ORDER BY u.id
";
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
    <div class="header"><div class="brand"><h1>Users</h1></div><div class="nav"><a href="index.html">Home</a></div></div>
    <div class="card">
      <h3>Lista de usuarios</h3>
        <div class="list">
          <table style="width:100%;border-collapse:collapse">
            <thead style="text-align:left;color:#6b7280"><tr>
              <th>Fullname</th><th>Email</th><th>Ide</th><th>Phone</th><th>Birth</th><th>Issue</th><th>Status</th>
            </tr></thead>
            <tbody>
              <?php while($u = pg_fetch_assoc($res)): ?>
                <tr style="border-top:1px solid #f1f5f9">
                  <td><?=htmlspecialchars($u['firstname'].' '.$u['lastname'])?></td>
                  <td><?=htmlspecialchars($u['email'])?></td>
                  <td><?=htmlspecialchars($u['ide_number'])?></td>
                  <td><?=htmlspecialchars($u['mobile_number'])?></td>
                  <td><?=htmlspecialchars($u['city_birth'])?></td>
                  <td><?=htmlspecialchars($u['city_issue'])?></td>
                  <td><?= $u['status'] ? 'Active' : 'Inactive' ?></td>
                </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
    </div>
    <div class="footer">Datos sincronizados con la base de datos</div>
  </div>
</body>
</html>
