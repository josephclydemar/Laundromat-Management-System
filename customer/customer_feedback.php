<?php
    session_start();
    include_once "../classes/database_connection.php";
    include_once "customer.php";
    // include_once "message.php";

    $db_conn = new DatabaseConnection();
    $pdo = $db_conn->getPDO();


    if(isset($_SESSION['user_id']) && isset($_SESSION['order_id']))
    {
        $sql_select = "SELECT * FROM users WHERE user_id=:user_id";
	    $stmt = $pdo->prepare($sql_select);
        $stmt->execute(array(
                             ':user_id'=>$_SESSION['user_id']
                            ));
	    $user_info = $stmt->fetch(PDO::FETCH_ASSOC);
        $customer = new Customer($user_info['firstname'], $user_info['lastname'], $user_info['user_address'], $user_info['email'], $user_info['user_password']);
        $customer_messages = $customer->getMyMessageThread($_SESSION['order_id']);
        echo '<h1>CUSTOMER SIDE</h1>';
        echo $user_info['user_id'].'<br>';
        echo $user_info['firstname'].'<br>';
        echo $user_info['lastname'].'<br>';
        echo $user_info['email'].'<br>';

        if(isset($_POST['message']) && isset($_POST['message_button']))
        {
            $date_today = date('Y-m-d H:i:s');
            $customer->createMessage($_SESSION['order_id'], $date_today, $_POST['message']);
            header('Location: customer_feedback.php');
            return;
        }
        
        if(isset($_GET['back']))
        {
            header('Location: customer_dashboard.php');
            return;
        }
    }
    else
    {
        header('Location: customer_dashboard.php');
        return;
    }


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form method="GET">
        <input type="submit" name="back" value="<< BACK">
    </form>
    <h3>
        <?php
            echo 'ORDER_ID: '.$_SESSION['order_id'];
        ?>
    </h3>
    <?php
        echo '<div id="get_order_id">'.$_SESSION['order_id'].'</div><div id="get_user_id">'.$_SESSION['user_id'].'</div>';
    ?>
    <div id="messages">
        <?php
        if($customer_messages)
        {
            foreach($customer_messages as $key => $value)
            {
                $sql_select = "SELECT * FROM users WHERE user_id=:user_id";
	            $stmt = $pdo->prepare($sql_select);
                $stmt->execute(array(
                                     ':user_id'=>$value['user_id']
                                    ));
	            $user_info = $stmt->fetch(PDO::FETCH_ASSOC);
                echo '<div class="user_type_'.$user_info['user_type'].'">'.$value['message'].'</div>';
            }
        }
        ?>
    </div>
    <form method="POST">
        <input type="text" name="message" required>
        <input type="submit" name="message_button" value="Send">
    </form>
    <style>
        #messages {
            overflow-y: scroll;
            height: 250px;
        }
        .user_type_0 {
            text-align: right;
            background-color: #018;
            color: #fff;
            padding: 2px;
            margin-top: 2px;
            margin-bottom: 2px;
        }
        .user_type_1 {
            text-align: left;
            background-color: #081;
            color: #fff;
            padding: 2PX;
            margin-top: 2px;
            margin-bottom: 2px;
        }
        #messages {
            width: 300px;
        }
    </style>
    <script src="js_scripts/customer_messages_script.js"></script>
</body>
</html>