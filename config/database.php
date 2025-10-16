<?php
// config/database.php
// Ajusta credenciales si son distintas

// Supabase (ejemplo)
$supa_host       = "aws-1-us-east-1.pooler.supabase.com";
$supa_user       = "postgres.aaclbcyxyyuamhhyvoux";
$supa_password   = "unicesmag@@";
$supa_dbname     = "postgres";
$supa_port       = "6543";

// Local
$local_host      = "127.0.0.1";
$local_user      = "postgres";
$local_password  = "unicesmag";
$local_dbname    = "marketapp";
$local_port      = "5432";

// Connection strings
$supa_conn_str = "host=$supa_host user=$supa_user password=$supa_password dbname=$supa_dbname port=$supa_port";
$local_conn_str = "host=$local_host user=$local_user password=$local_password dbname=$local_dbname port=$local_port";

// Try supabase first, otherwise local
$conn_supa = @pg_connect($supa_conn_str);
$conn_local = @pg_connect($local_conn_str);

$conn = $conn_supa ? $conn_supa : $conn_local;

if (!$conn) {
  // fatal: no connection
  die("Error: cannot connect to database. Check config/database.php credentials.");
}
?>
