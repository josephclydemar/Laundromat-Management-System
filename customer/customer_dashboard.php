<?php
    session_start();
    include_once "../classes/database_connection.php";
    include_once "customer.php";

    $db_conn = new DatabaseConnection();
    $pdo = $db_conn->getPDO();
    // echo $_SESSION['user_id'];
    // echo 'SA GAWAS!!!!';
    $sql_admin_select = "SELECT * FROM users WHERE user_type=:user_type";
	$stmt = $pdo->prepare($sql_admin_select);
    $stmt->execute(array(
                         ':user_type'=>1
                        ));
	$admin_info = $stmt->fetch(PDO::FETCH_ASSOC);

    $order_status_words = array(1=>'Complete', 2=>'In-Progress', 3=>'Pending');
    if(isset($_GET['logout']))
    {
        session_destroy();
		header("Location: ../index.php");
		return;
    }

    if(isset($_SESSION['user_id']))
    {
        // echo $_SESSION['user_id'];

        $sql_select_services = "SELECT * FROM services";
	    $stmt = $pdo->query($sql_select_services);
	    $all_services_info = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $sql_select = "SELECT * FROM users WHERE user_id=:user_id";
	    $stmt = $pdo->prepare($sql_select);
        $stmt->execute(array(':user_id'=>$_SESSION['user_id']));
	    $user_info = $stmt->fetch(PDO::FETCH_ASSOC);

        if($user_info['user_type'] == 1)
        {
            header('Location: ../admin/admin_dashboard.php');
            return;
        }

        $customer = new Customer($user_info['firstname'], $user_info['lastname'], $user_info['user_address'], $user_info['email'], $user_info['user_password']);
        // echo $_SESSION['user_id'];
        // echo 'TRRUUEUUEE';
        if( isset($_POST['service_type']) && isset($_POST['weight']) && isset($_POST['order_price']) && isset($_POST['order_duration']) )
        {
            $time_today_SAYUP = date('d-m-Y H:i:s');

            // $time_remaining = ((int)$_POST['order_duration']) * 3600;
            $time_remaining = ((int)$_POST['order_duration']) * 60;
            // $time_remaining = ((int)$_POST['order_duration']);

            $order_status = 2; // Set Order Status to In-progress
            $time_offset = (int)$_POST['order_duration'];

            $date_and_time_split = explode(' ', $time_today_SAYUP);
            $hour_minute_second_split = explode(':', $date_and_time_split[1]);

            $date_split_for_database_format = explode('-', $date_and_time_split[0]);
            $time_today = $date_split_for_database_format[2].'-'.$date_split_for_database_format[1].'-'.$date_split_for_database_format[0].' '.$date_and_time_split[1];

            // $date_day_split_for_database_format = (int)$date_split_for_database_format[0];
            // $time_duration = ((int)$hour_minute_second_split[0]) + $time_offset;
            $time_duration = ((int)$hour_minute_second_split[1]) + $time_offset;
            // $time_duration = ((int)$hour_minute_second_split[2]) + $time_offset;
   
            // $date_finish = $date_split_for_database_format[2].'-'.$date_split_for_database_format[1].'-'.($date_split_for_database_format[0]).' '.$time_duration.':'.$hour_minute_second_split[1].':'.$hour_minute_second_split[2];
            $date_finish = $date_split_for_database_format[2].'-'.$date_split_for_database_format[1].'-'.($date_split_for_database_format[0]).' '.$hour_minute_second_split[0].':'.$time_duration.':'.$hour_minute_second_split[2];
            // $date_finish = $date_split_for_database_format[2].'-'.$date_split_for_database_format[1].'-'.($date_split_for_database_format[0]).' '.$hour_minute_second_split[0].':'.$hour_minute_second_split[1].':'.$time_duration;
            
            $customer->createOrder($_POST['service_type'], $time_today, $date_finish, $time_remaining, $order_status, $_POST['weight'], 'Hoy HEHE', $_POST['order_price']);
            header('Location: customer_dashboard.php');
            return;
        }

        $select_query_result_orders = $customer->getMyOrders(1);
        $select_query_result_user = $customer->getMyInfo();

        // getting all complete orders
        $sql_select_complete_orders = "SELECT order_id, order_status FROM orders WHERE user_id=:user_id AND order_status=:order_status";
	    $stmt = $pdo->prepare($sql_select_complete_orders);
        $stmt->execute(array(':user_id'=>$customer->getUserId(), ':order_status'=>1));
	    $select_query_result_all_complete_orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $sql_select_inprogress_orders = "SELECT order_id, order_status FROM orders WHERE user_id=:user_id AND order_status=:order_status";
	    $stmt = $pdo->prepare($sql_select_inprogress_orders);
        $stmt->execute(array(':user_id'=>$customer->getUserId(), ':order_status'=>2));
	    $select_query_result_all_inprogress_orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $sql_select_pending_orders = "SELECT order_id, order_status FROM orders WHERE user_id=:user_id AND order_status=:order_status";
	    $stmt = $pdo->prepare($sql_select_pending_orders);
        $stmt->execute(array(':user_id'=>$customer->getUserId(), ':order_status'=>3));
	    $select_query_result_all_pending_orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if(isset($_POST['user_id']) && isset($_POST['order_id']))
        {
            $_SESSION['user_id'] = $_POST['user_id'];
            $_SESSION['order_id'] = $_POST['order_id'];
            header('Location: customer_feedback.php');
            return;
        }
    }
    else {
        header('Location: ../index.php');
        return;
    }
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WashMatic</title>
    <link rel="icon" type="image/x-icon" href="imgs/washing-machine_icon-icons.com_60734.ico">
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous"> -->
</head>
<body onload="putPriceValue()">
    
    <header class="header">
        <div class="logo">
            <h4>Wash Matic</h4>
        </div>

        <div class="dashboard">
            <h5>CUSTOMER SIDE</h5>
        </div>

        <div class="user">
            <?php
            echo '<span style="font-weight: bold;">'.$select_query_result_user['firstname'].' '.$select_query_result_user['lastname'].'</span>';
            echo '<br>';
            echo '<span style="font-weight: bold;">'.$select_query_result_user['user_id'].'</span>';
            ?>
        </div>
    </header>
    <div class="container">
        <div class="side-bar">
            <span>Main Menu</span>
            <a href="#">Dashboard</a>
            <a href="../history.php">History</a>
            <form method="GET">
                <input type="submit" name="logout" value="Log-out">
            </form>
        </div>
        <div class="main-container">
            <div class="first-container">
                <div class="order">
                        <div class="place-order-inner">
                            <h3>PLACE ORDER</h2>
                        </div>
                        <div class="place-order-body">
                            <form action="customer_dashboard.php" method="POST">
                                <!-- Form content goes here -->
                                <select name="service_type" id="services" onchange="getOrderWeightValue()" required>
                                    <option value="" disabled selected hidden>Select service</option>
                                    <option value="1" selected>Press only</option> 
                                    <option value="2">Wash-Dry-Fold</option>
                                    <option value="3">Wash-Dry-Press</option>
                                </select><br>
                                <input type="number" name="weight" id="weight" min="1" max="20" autocomplete="off" placeholder="Input weight" value="1" required>kg<br>
                                
                                <input type="hidden" id="order_price" name="order_price" value="">
                                <input type="hidden" id="order_duration" name="order_duration" value="">
                                <input type="submit" name="btn" value="Submit" class="place_order_button">
                            </form>
                            <span style="font-size: 13px; font-weight: 800;">DURATION:</span><span id="order_duration_label" style="font-size:16px;font-weight:800;"></span><span style="font-size:16px;font-weight:800;">hr</span><br>
                            <span style="font-size: 13px; font-weight: 800;">PRICE:</span><span id="order_price_label" style="font-size:16px;font-weight:800;">0</span><br>
                        </div>
                </div>

                <div class="list">
                    <div class="card1">
                        <div class="card1-body">
                                <table>
                                    <tr>
                                        <th>Order Number</th>
                                        <th style="background: green;">Complete</th>
                                    </tr>
                                    <?php
                                    if($select_query_result_all_complete_orders) {
                                        foreach($select_query_result_all_complete_orders as $record) {
                                            echo '<tr>';
                                            echo '<td>'.$record['order_id'].'</td>';
                                            echo '<td>'.$record['order_status'].'</td>';
                                            echo '</tr>';
                                        }
                                    }
                                    ?>
                                </table>
                                </div>
                            
                      </div>
                      <div class="card2">
                        <div class="card2-body">
                        <table>
                            <tr>
                                <th>Order Number</th>
                                <th style="background-color: #43A6ED;">In-Progress</th>
                            </tr>
                            <?php
                                if($select_query_result_all_inprogress_orders) {
                                    foreach($select_query_result_all_inprogress_orders as $record) {
                                        echo '<tr>';
                                        echo '<td>'.$record['order_id'].'</td>';
                                        echo '<td>'.$record['order_status'].'</td>';
                                        echo '</tr>';
                                    }
                                }
                            ?>
                        </table>
                        </div>
                      </div>
                      <div class="card3">
                        <div class="card3-body">
                        <table>
                            <tr>
                                <th>Order Number</th>
                                <th style="background: rgb(255,37,0);">Pending</th>
                            </tr>
                            <?php
                                if($select_query_result_all_pending_orders) {
                                    foreach($select_query_result_all_pending_orders as $record) {
                                        echo '<tr>';
                                        echo '<td>'.$record['order_id'].'</td>';
                                        echo '<td>'.$record['order_status'].'</td>';
                                        echo '</tr>';
                                    }
                                }
                            ?>
                        </table>
                        </div>
                      </div>
                </div>
            </div>
            <div class="second-container">
                
                    <div class="my-order-inner">
                        
                    </div>
                    <h3 style="text-align: center;">My Orders</h4>
                    <div class="my-order-body">
                        <table>
                            <tr>
                                <th>Order ID</th>
                                <th>Date</th>
                                <th>Remaining Time</th>
                                <th>Type of Service</th>
                                <th>Amount (₱)</th>
                                <th>Status</th>
                                <th>Feedback</th>
                            </tr>
                            <?php
                            if(isset($select_query_result_orders)) {
                                foreach($select_query_result_orders as $record) {
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
                                    echo '<td><form method="POST"><input type="hidden" name="order_id" value="'.$record['order_id'].'"><input type="hidden" name="user_id" value="'.$record['user_id'].'"><input class="feedback_button" id="notification_'.$record['order_id'].'_'.$admin_info['user_id'].'" type="submit" name="feedback" value="Comment"></form></td>';
                                    echo '</tr>';
                                    
                                }
                            }
                            ?>
                        </table>
                    </div>
                
            </div>
            
            
        </div>

    </div>
    
    <style>
        /* CSS styles go here */
        .order{
            flex: 1;
        }

        .list{
            display: flex;
            
        }
        /* @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap"); */
        *{
            margin: 0;
            padding: 0;
            border: none;
            outline: none;
            text-decoration: none;
            box-sizing: border-box;
            font-family:'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
        }
        body{
                /* background: rgb(219, 219, 219); */
                background: rgb(204,204,204);
                
            }
        
            .feedback_button {
              background-color: #e1ecf4;
              border-radius: 8px;
              border: 1px solid #7aa7c7;
              box-shadow: rgba(255, 255, 255, .7) 0 1px 0 0 inset;
              box-sizing: border-box;
              color: #39739d;
              cursor: pointer;
              display: inline-block;
              font-family: -apple-system,system-ui,"Segoe UI","Liberation Sans",sans-serif;
              font-size: 13px;
              font-weight: 900;
              line-height: 1.15385;
              margin: 0;
              outline: none;
              padding: 8px;
              position: relative;
              text-align: center;
              text-decoration: none;
              user-select: none;
              -webkit-user-select: none;
              touch-action: manipulation;
              vertical-align: baseline;
              white-space: nowrap;
            }
    
        .header{
                display: flex;
                align-items: center;
                justify-content: space-between;
                height: 60px;
                /* padding: 20px; */
                /* padding-right: 20px; */
                background: #fff;
                flex-direction: row;
                width: 100%;
            }
        .logo{
                width: 15%;
                height: 60px;
                background-color: #43A6ED;
                text-align: center;
                padding: 15px;
                font-size: 22px;
                
        }
        
        .user{
                width: 15%;
                height: 60px;
                background-color: #43A6ED;
                padding-left: 30px;
                padding-top: 15px;
                font-size: 17px;
                text-align: center;
        }
    
        .dashboard{
            width: auto;
            font-size: 25px;
    
        }
    
        .container{
        margin-top: 10px;
        display: flex;
        height: 92.5vh;
        justify-content: space-between;
        }
        .side-bar{
            padding: 1px;
            display: flex;
            flex-direction: column;
            height: 100%;
            width: 15%;
            background: #43A6ED;
        }
        
        .side-bar span{
            color: black;
            margin: 1.8rem 3rem;
            font-size: 12px;
        }
    
        .side-bar a{
            width: 100%;
            padding: 1.8rem 3rem;
            font-weight: 500;
            font-size: 18px;
            color: black;        
        }
    
        .side-bar input{
            width: 98%;
            padding: 1.8rem 3rem;
            font-weight: 500;
            font-size: 18px;
            color: black;        
            background: #43A6ED;
        }
    
        .side-bar a:hover{
            background: #fff;
            color: #43A6ED;
            border-radius: 20px;
        
        }
    
        .side-bar input:hover{
            background: #fff;
            color: #43A6ED;
            border-radius: 20px;
        
        }
    
        .main-container{
            display: flex;
            padding: 10px;
            height: 100%;
            width: 100%;
            flex-direction: column;
        }
    
        .first-container .list{
            display: flex;
            align-items: flex-start;
            width: 70%;
            height: 100%;
            flex-direction: row;
            /* gap: 10px; */
            justify-content: space-between;
            border: 0.5px solid black;
            padding: 5px;
            background: #fff;
            border-radius: 10px;
            /* background-color: #43A6ED; */
            /* background-color: #4351ed; */
        }
        .first-container .list .card1{
            width: 30%;
            height: 100%;
            /* border: 0.5px solid black; */
            overflow: auto;
        }
        
        
        
        .first-container .list .card1 table{
            width: 100%;
            height: 100%;
            /* color: white; */
            /* border: 0.5px solid black; */
        }
    
        .first-container .list .card1 table, th, td{
            border: 1px solid;
            border-collapse: collapse;
            border-color: black;
            text-align: center;
            padding: 2px;
        }
        .first-container .list .card2{
            width: 30%;
            height: 100%;
            /* border: 0.5px solid black; */
            overflow: auto;
        }
        .first-container .list .card2 table, th, td{
            border: 1px solid;
            border-collapse: collapse;
            border-color: black;
            text-align: center;
            padding: 2px;
        }
        .first-container .list .card3{
            width: 30%;
            height: 100%;
            /* border: 0.5px solid black; */
            overflow: auto;
        }
        .first-container .list .card3 table, th, td{
            border: 1px solid;
            border-collapse: collapse;
            border-color: black;
            text-align: center;
            padding: 2px;
        }
    
    
        .second-container{
            width: 100%;
            height: 70%;
        }
        
    
    
        
        
    
        .first-container .order{
            width: auto;
            align-items: center;
            text-align: center;
        }
    
        .first-container{
            flex-direction: row;
            justify-content: space-between;
            height: 30%;
            width: 100%;
            display: flex;
        }
    
        .first-container .order .place-order-inner{
            text-align: center;
        }
    
        .first-container .order .place-order-body{
            text-align: center;
            align-items: center;
        }
        .first-container .order .place-order .place-order-body{
            padding-top: 5px;
            align-items: center;
            text-align: center;
        }
        select{
            height: 30px;
            font-size: 17px;
            margin: 10px;
        }
        input{
            height: 30px;
            font-size: 17px;
            margin: 5px;
        }
    
        label{
            height: 30px;
            font-size: 17px;
            margin: 10px;
        }
        .place_order_button {
            height: 30px;
            width: 20%;
            font-size: 15px;
            background-color: #43A6ED;
            margin: 5px;
            border: 0;
            border-radius: 5px;
        }
        .place_order_button:hover {
            border: #000 solid 1px;
        }
    
        .second-container .my-order-body table{
            width: 100%;
        }
    
        .second-container .my-order-body{
            width: 100%;
            padding: 20px;
            height: 60%;
            overflow: auto;
            border: 0.5px solid black;
            background: #fff;
            border-radius: 20px;
        }
    
        .second-container .my-order-inner{
            padding-left: 80px;
            padding-top: 50px;
            padding-bottom: 15px;
           
        }
    
        .second-container .my-order-inner h3{
            padding-left: 40px;
            
           
        }
    
        .second-container .my-order-body table, th, td{
            border: 1px solid;
            border-collapse: collapse;
            border-color: black;
            text-align: center;
            padding: 2px;
        }
    </style>
    <script src="js_scripts/customer_script.js"></script>
</body>
</html>
    
