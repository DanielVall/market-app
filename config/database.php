<?php
    //Database connection
    $host       = "127.0.0.1"; //
    $user       = "postgres";
    $password   = "unicesmag";
    $dbname     = "marketapp";
    $port       = "5432";

    $data_connection = "
        host      =$host
        user      =$user
        password  =$password
        dbname    =$dbname
        port      =$port
    ";
    
    $conn = pg_connect($data_connection);

    if(!$conn){
        echo "Error";
    }else{
        echo"Connection succesfully! :::";
    }

?>