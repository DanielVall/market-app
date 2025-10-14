<?php
//Step 1. database access --> registrar datos
    require ('../config/database.php');




    ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marketapp - List users</title>
</head>
<body>
    <table border= "1" align = "center">
        
    <tr>
    <th>Fullname</th>
    <th>E-mail</th>
    <th>Ide.number</th>
    <th>Phone number</th>
    <th>Status</th>
    <th>Options</th>
</tr>
<?php
    sql_users = "
        //
    ";
?>
     <tr>
    <td>Daniel</td>
    <td>Daniel@gmail.com</td>
    <td>1080691904</td>
    <td>3216084861</td>
    <td>Active</td>
    <td>
        <a href = "#">
            <img src = "icons/search.png" width="16" >
        <a href = "#">Uptade </a>
        <a href = "#">Delete </a>
</td>
        <tr>

    </table>
</body>
</html>