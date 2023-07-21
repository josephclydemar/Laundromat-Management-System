<?php
    session_start();
    include_once "database_connection.php";
    include_once "customer.php";

    $db_conn = new DatabaseConnection();
    $pdo = $db_conn->getPDO();

    if(isset($_SESSION['user_id']))
    {
        $sql_select = "SELECT * FROM users WHERE user_id=:user_id";
	    $stmt = $pdo->prepare($sql_select);
        $stmt->execute(array(':user_id'=>$_SESSION['user_id']));
	    $user_info = $stmt->fetch(PDO::FETCH_ASSOC);

        if($user_info['user_type'] == 0)
        {
            header('Location: customer_dashboard.php');
            return;
        }
        
        foreach($user_info as $v)
        {
            echo $v.'<br>';
        }
    }
    else
    {
        header('Location: index.php');
        return;
    }
?>