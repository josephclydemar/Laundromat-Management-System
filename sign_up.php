<?php
    session_start();
    
    include_once "database_connection.php";
    include_once "customer.php";

    function verifyEmailAlreadyExists($entered_email)
    {
        $db_connection = new DatabaseConnection();
        $pdo = $db_connection->getPDO();

        $sql_select = "SELECT * FROM users WHERE emaiL=:email";
	    $stmt = $pdo->prepare($sql_select);
        $stmt->execute(array(':email'=>$entered_email));
	    $select_query_result = $stmt->fetch(PDO::FETCH_ASSOC);
        // var_dump($select_query_result);
        return $select_query_result;
    }

    if(isset($_POST['email']) && isset($_POST['user_password'])) {
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $user_address = $_POST['user_address'];
        $email = $_POST['email'];
        $user_password = $_POST['user_password'];

        $email_already_exists = verifyEmailAlreadyExists($email);
        if($email_already_exists)
        {
            header('Location: index.php');
            return;
        }
        else
        {
            $new_customer = new Customer($firstname, $lastname, $user_address, $email, $user_password);
            $user_id = $new_customer->getuserId();
            // echo $new_customer->getuserId();
            $_SESSION['user_id'] = $user_id;
            header('Location: customer_dashboard.php');
            return;
        }
    }
?>