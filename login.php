<?php
    session_start();
    include_once "database_connection.php";

    function authenticateUser($entered_email, $entered_password)
    {
        $db_connection = new DatabaseConnection();
        $pdo = $db_connection->getPDO();
        $sql_select = "SELECT * FROM users WHERE email=:email";
        $stmt = $pdo->prepare($sql_select);
        $stmt->execute(array(':email'=>$entered_email));
	    $select_query_result = $stmt->fetch(PDO::FETCH_ASSOC);
        if($select_query_result)
        {
            $recorded_password = $select_query_result['user_password'];
            if($recorded_password == $entered_password)
            {
                // Account exists, correct password.
                return $select_query_result['user_id'];
            }
            else
            {
                // Incorrect password.
                return 0;
            }
        }
        else
        {
            // Account does not exist.
            return 0;
        }
    }

    if(isset($_POST)) {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $id_authenticated_user = authenticateUser($email, $password);
        if($id_authenticated_user != 0) {
            if(isset($_SESSION['user_id'])) {
                $_SESSION['user_id'] = $id_authenticated_user;
            }
            header('Location: customer_track_orders.php');
            return;
        }
        else
        {
            header('Location: index.php');
            return;
        }
    }
?>