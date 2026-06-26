<?php 
    require_once 'auth.php';
    
    session_unset();

    session_destroy();

    header( "refresh:0;url=login.php" );
?>