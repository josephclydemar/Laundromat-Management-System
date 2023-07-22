<?php
    session_start();
    include_once "../classes/database_connection.php";
    include_once "admin.php";

    $db_conn = new DatabaseConnection();
    $pdo = $db_conn->getPDO();

    if(isset($_SESSION['customer_id']) && isset($_SESSION['admin_id']))
    {
        if(isset($_POST['back']))
        {
            header('Location: admin_dashboard.php');
            return;
        }
        $order_status_words = array(1=>'Complete', 2=>'In-Progress', 3=>'Pending');

        $sql_select_services = "SELECT * FROM services";
	    $stmt = $pdo->query($sql_select_services);
	    $all_services_info = $stmt->fetchAll(PDO::FETCH_ASSOC);


        $sql_select = "SELECT * FROM users WHERE user_id=:user_id";
	    $stmt = $pdo->prepare($sql_select);
        $stmt->execute(array(':user_id'=>$_SESSION['admin_id']));
	    $user_info = $stmt->fetch(PDO::FETCH_ASSOC);

        $admin = new Admin($user_info['firstname'], $user_info['lastname'], $user_info['user_address'], $user_info['email'], $user_info['user_password']);
        $my_info = $admin->getMyInfo();
        echo $my_info['user_id'].'<br>';
        echo $my_info['firstname'].'<br>';
        echo $my_info['lastname'].'<br>';
        echo $my_info['email'].'<br>';


        echo '<br><br>Customer Id: '.$_SESSION['customer_id'];

        $select_all_the_customer_orders = $admin->getCustomerOrders($_SESSION['customer_id']);
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
    <div>
        <table>
            <tr>
                <th>Order ID</th>
                <th>Date Ordered</th>
                <th>Remaining Time</th>
                <th>Service Type</th>
                <th>Payment Amount (P)</th>
                <th>Status</th>
                <th>Customer Feedback</th>
            </tr>
            <?php
                if(isset($select_all_the_customer_orders)) {
                    foreach($select_all_the_customer_orders as $record) {
                        $sql_select_payment = "SELECT * FROM payments WHERE order_id=:order_id";
	                    $stmt = $pdo->prepare($sql_select_payment);
                        $stmt->execute(array(':order_id'=>$record['order_id']));
	                    $payment_info = $stmt->fetch(PDO::FETCH_ASSOC);

                        echo '<tr>';
                        echo '<td>'.$record['order_id'].'</td>';
                        echo '<td>'.$record['date_ordered'].'</td>';
                        echo '<td class="remaining_time_class" id="_'.$record['order_id'].'">'.$record['remaining_time'].'</td>';
                        // echo '<td>'.($time_to_finish-$time_now).'</td>';
                        echo '<td>'.$all_services_info[$record['service_id']-1]['service_name'].'</td>';
                        echo '<td>'.$payment_info['payment_amount'].'</td>';
                        echo '<td>'.$order_status_words[$record['order_status']].'</td>';
                        echo '<td><form method="POST"><input type="hidden" name="order_id" value="'.$record['order_id'].'"><input type="hidden" name="user_id" value="'.$record['user_id'].'"><input type="submit" name="feedback" value="See Comment"></form></td>';
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
    <script src="js_scripts/admin_scripts.js"></script>
</body>
</html>


