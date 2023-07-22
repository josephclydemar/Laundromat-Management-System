<?php
    session_start();
    include_once "../classes/database_connection.php";
    include_once "admin.php";

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

        $admin = new Admin($user_info['firstname'], $user_info['lastname'], $user_info['user_address'], $user_info['email'], $user_info['user_password']);
        $my_info = $admin->getMyInfo();
        echo '<h1>ADMIN SIDE</h1>';
        echo $my_info['user_id'].'<br>';
        echo $my_info['firstname'].'<br>';
        echo $my_info['lastname'].'<br>';
        echo $my_info['email'].'<br>';
        echo '<br>';

        $all_my_customers = $admin->getMyCustomers();

        if(isset($_POST['customer_id']) && isset($_POST['admin_id']) && isset($_POST['customer_orders_button']))
        {
            $_SESSION['customer_id'] = $_POST['customer_id'];
            $_SESSION['admin_id'] = $_POST['admin_id'];
            header('Location: admin_view_customer_orders.php');
            return;
        }
    }
    else
    {
        header('Location: index.php');
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
    <div id="customers">
        <table>
            <tr>
                <th>Customer ID</th>
                <th>Firstname</th>
                <th>Lastname</th>
                <th>Email</th>
                <th>Customer Orders</th>
            </tr>
            <?php
                if(isset($all_my_customers))
                {
                    foreach($all_my_customers as $record)
                    {
                        echo '<tr>';
                        echo '<td>'.$record['user_id'].'</td>';
                        echo '<td>'.$record['firstname'].'</td>';
                        echo '<td>'.$record['lastname'].'</td>';
                        echo '<td>'.$record['email'].'</td>';
                        echo '<td><form method="POST"><input type="hidden" name="customer_id" value="'.$record['user_id'].'"><input type="hidden" name="admin_id" value="'.$my_info['user_id'].'"><input type="submit" name="customer_orders_button" value="See Orders"></form></td>';
                        echo '</tr>';
                    }
                }
            ?>
        </table>
    </div>
    <style>
        table {
            border: 1px solid #000;
        }
        th {
            border: 1px solid #000;
        }
        td {
            border: 1px solid #000;
        }
    </style>
</body>
</html>