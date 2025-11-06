<?php
    //start or continue with current session
    session_start();

    session_unset();
    
    //Redirect to login form
    session_destroy();
    header("Location: signin.php");
?>