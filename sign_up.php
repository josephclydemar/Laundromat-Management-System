<?php
    session_start();
    include_once "database_connection.php";
    include_once "customer.php";

    function verifyEmailIsUnique($email)
    {
        $db_connection = new DatabaseConnection();
        $pdo = $db_connection->getPDO();
        $sql_select = "SELECT * FROM customers WHERE emaiL=:email";
	    $stmt = $pdo->prepare($sql_select);
        $stmt->execute(array(':email'=>$email));
	    $select_query_result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $select_query_result;
    }

    if(isset($_POST)) {
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $customer_address = $_POST['customer_address'];
        $email = $_POST['email'];
        $customer_password = $_POST['customer_password'];

        $emailIsUnique = verifyEmailIsUnique($email);
        if(!$emailIsUnique)
        {
            $new_customer = new Customer($firstname, $lastname, $customer_address, $email, $customer_password);
            $customer_id = $new_customer->getCustomerId();
            $_SESSION['customer_id'] = $customer_id;
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