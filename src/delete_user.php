<?php
//Step 1. Get database conncection
require_once __DIR__ . '/../config/database.php';

//Step 2. get data or params
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<script>alert('ID inválido.'); window.location='list_users.php';</script>";
    exit;
}

$user_id = intval($_GET['id']);

// Step 3. Preapare query
$sql_delete = "DELETE FROM users WHERE id = $user_id";

//step 4. Execute query
if (pg_query($conn, $sql_delete)) {
    echo "<script>alert('✅ Usuario eliminado correctamente'); window.location='list_users.php';</script>";
} else {
    echo "<script>alert('❌ Error al eliminar el usuario'); window.location='list_users.php';</script>";
}
?>
