<?php
    include_once "../database_connection.php";
    $db_conn = new DatabaseConnection();
    $pdo = $db_conn->getPDO();

    $user_address = 'Venus';
    $insert_new_user = "DELETE FROM users WHERE user_address=:user_address";
    $stmt = $pdo->prepare($insert_new_user);
    $stmt->execute(array(':user_address'=>$user_address));
?>