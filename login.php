<?php
    session_start();
    // echo 'username: '.$_POST['username'].'<br>';
    // echo 'password: '.$_POST['password'].'<br>';
    if(isset($_POST)) { 
        $_SESSION['username'] = $_POST['username'];
        $_SESSION['password'] = $_POST['password'];
        header('Location: index.php');
        return;
    }
?>