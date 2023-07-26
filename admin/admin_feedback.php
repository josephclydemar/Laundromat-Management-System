<?php
    session_start();
    include_once "../classes/database_connection.php";
    include_once "../classes/message.php";
    include_once "admin.php";

    $db_conn = new DatabaseConnection();
    $pdo = $db_conn->getPDO();

    if( isset($_SESSION['order_id']) && isset($_SESSION['customer_id']) && isset($_SESSION['admin_id']) )
    {
        if(isset($_POST['back']))
        {
            header('Location: admin_view_customer_orders.php');
            return;
        }
        if(isset($_POST['back_dash']))
        {
            header('Location: admin_dashboard.php');
            return;
        }


        $sql_select = "SELECT * FROM users WHERE user_id=:user_id";
	    $stmt = $pdo->prepare($sql_select);
        $stmt->execute(array(':user_id'=>$_SESSION['admin_id']));
	    $user_info = $stmt->fetch(PDO::FETCH_ASSOC);

        $admin = new Admin($user_info['firstname'], $user_info['lastname'], $user_info['user_address'], $user_info['email'], $user_info['user_password']);
        $my_info = $admin->getMyInfo();
        echo '<h1>ADMIN SIDE</h1>';
        echo $my_info['user_id'].'<br>';
        echo $my_info['firstname'].'<br>';
        echo $my_info['lastname'].'<br>';
        echo $my_info['email'].'<br>';

        echo '<h3>Customer Id: '.$_SESSION['customer_id'].'</h3>';
        echo '<h4>Customer Feedback on:<br>Order Id: '.$_SESSION['order_id'].'</h2>';

        $customer_mesages = $admin->getOrderMessages($_SESSION['order_id']);

        if( isset($_POST['message']) && isset($_POST['message_button']) )
        {
            $date_today = date('Y-m-d H:i:s');
            $admin->createMessage($_SESSION['order_id'], $date_today, $_POST['message']);
            header('Location: admin_feedback.php');
            return;
        }
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
    <form method="POST">
        <input type="submit" name="back" value="<< BACK">
    </form>
    <form method="POST">
        <input type="submit" name="back_dash" value="<< BACK DASHBOARD">
    </form>
    <?php
        echo '<div id="get_order_id">'.$_SESSION['order_id'].'</div><div id="get_user_id">'.$_SESSION['admin_id'].'</div>';
    ?>
    <div id="messages">
        <?php
        if(isset($customer_mesages))
        {
            foreach($customer_mesages as $key => $value)
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
            height: 220px;
        }
        .user_type_0 {
            text-align: left;
            background-color: #081;
            color: #fff;
            padding: 2px;
            margin-top: 2px;
            margin-bottom: 2px;
        }
        .user_type_1 {
            text-align: right;
            background-color: #018;
            color: #fff;
            padding: 2PX;
            margin-top: 2px;
            margin-bottom: 2px;
        }
        #messages {
            width: 300px;
        }
    </style>
    <script src="js_scripts/admin_messages_script.js"></script>
</body>
</html>