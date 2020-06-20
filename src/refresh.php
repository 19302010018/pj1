<?php
    session_start();
    $_SESSION['refresh'] = true;
    header("Location:".$_SERVER['HTTP_REFERER']);
    ?>