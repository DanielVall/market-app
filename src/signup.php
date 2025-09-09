<?php
    //Step 1. database access --> registrar datos
    require ('../config/database.php');

    //Step 2. Get from Data --> traer los datos del formulario
    $f_name = $_POST ['fname'];
    $l_name = $_POST ['lname'];
    $m_number = $_POST ['mnumber'];
    $id_number = $_POST ['idnumber'];
    $e_mail = $_POST ['email'];
    $p_wd = $_POST ['passwd'];

    //Step 3. Create query to insert into --> Crear una query
    $query = "
    INSERT INTO users (
        firstname,
        lastname,
        mobile_number,
        ide_number,
        email,
        password
    ) VALUES (
        '$f_name', 
        '$l_name', 
        '$m_number', 
        '$id_number', 
        '$e_mail', 
        '$p_wd'
        )
    ";

    //Step 4. --> ejecucion del query
    $res = pg_query ($conn, $query);

    //Step 5. Validate result
    if ($res){
        echo "User has been created sucessfully !!!";
    } else{
        echo "Something wrong!";
    }
?>