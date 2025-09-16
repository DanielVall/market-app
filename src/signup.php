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

    $enc_pass = password_hash($p_wd, PASSWORD_DEFAULT);

    $check_email = "
        SELECT
            u.email
        FROM
            users u
        WHERE
            email = '$e_mail' or '$ide_number' = '$id_number' 
        LIMIT 1 
    ";
    $res_check= pg_query ($conn, $check_email);
    if(pg_num_rows($res_check) > 0){
        echo "<script>alert('User already exists !!! Go to login')</script>";
        header ('refresh:0;url=signup.html');
    } else {
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
        '$enc_pass'
        )
    ";

    //Step 4. --> ejecucion del query
    $res = pg_query ($conn, $query);

    //Step 5. Validate result
    if ($res){
        //echo "User has been created sucessfully !!!";
        echo "<script>alert('Succees !!! Go to login')</script>";
        header ('refresh:0;url=signin.html');
    } else{
        echo "Something wrong!";
    }
    }

    
?>