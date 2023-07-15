<?php
    session_start();
    include_once "database_connection.php";
    include_once "customer.php";

    $db_conn = new DatabaseConnection();
    $pdo = $db_conn->getPDO();
    // echo $_SESSION['user_id'];
    // echo 'SA GAWAS!!!!';
    if(isset($_GET['logout']))
    {
        session_destroy();
		header("Location: index.php");
		return;
    }

    if(isset($_SESSION['user_id']))
    {
        // echo $_SESSION['user_id'];
        // $sql_select = "SELECT * FROM users WHERE user_id=:user_id";
	    // $stmt = $pdo->prepare($sql_select);
        // $stmt->execute(array(':user_id'=>$_SESSION['user_id']));
	    // $select_query_result = $stmt->fetch(PDO::FETCH_ASSOC);
        // foreach($select_query_result as $key => $value) {
        //     echo $key.' : '.$value.'<br>';
        // }
        $sql_select = "SELECT * FROM users WHERE user_id=:user_id";
	    $stmt = $pdo->prepare($sql_select);
        $stmt->execute(array(':user_id'=>$_SESSION['user_id']));
	    $user_info = $stmt->fetch(PDO::FETCH_ASSOC);

        $customer = new Customer($user_info['firstname'], $user_info['lastname'], $user_info['user_address'], $user_info['email'], $user_info['user_password']);
        // echo $_SESSION['user_id'];
        // echo 'TRRUUEUUEE';
        if( isset($_POST['service_type']) && isset($_POST['weight']) && isset($_POST['order_price']) )
        {
            $time_today = date('Y-m-d H:i:s');
            $customer->createOrder($_POST['service_type'], $time_today, 2, 2, $_POST['weight'], 'Hoy BRAD', $_POST['order_price']);
        }
        $select_query_result_orders = $customer->getMyOrders();
        $select_query_result_user = $customer->getMyInfo();

        // getting all complete orders
        $sql_select_complete_orders = "SELECT order_id, order_status FROM orders WHERE order_status=:order_status";
	    $stmt = $pdo->prepare($sql_select_complete_orders);
        $stmt->execute(array(':order_status'=>1));
	    $select_query_result_all_complete_orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $sql_select_inprogress_orders = "SELECT order_id, order_status FROM orders WHERE order_status=:order_status";
	    $stmt = $pdo->prepare($sql_select_inprogress_orders);
        $stmt->execute(array(':order_status'=>2));
	    $select_query_result_all_inprogress_orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $sql_select_pending_orders = "SELECT order_id, order_status FROM orders WHERE order_status=:order_status";
	    $stmt = $pdo->prepare($sql_select_pending_orders);
        $stmt->execute(array(':order_status'=>3));
	    $select_query_result_all_pending_orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    else {
        // echo 'FAAAAALLLLSESS';
    }
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body onload="putPriceValue()">
    <header class="header">
        <div class="logo">
            Wash Matic
        </div>

        <div class="dashboard">
            DASHBOARD
        </div>

        <div class="user">
            <?php
            echo $select_query_result_user['firstname'].' '.$select_query_result_user['lastname'];
            ?>
        </div>
    </header>
    <div class="container">
        <div class="side-bar">
            <span>Main Menu</span>
            <a href="#">Dashboard</a>
            <a href="#">History</a>
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
                            <form method="POST">
                                <!-- Form content goes here -->
                                <select name="service_type" id="services" onchange="getOrderWeightValue()" required>
                                    <option value="0" disabled selected>Service Type</option> 
                                    <option value="2">Wash-Dry-Fold</option> 
                                    <option value="3">Wash-Dry-Press</option> 
                                    <option value="1">Press only</option>  
                                </select><br>
                                <input type="number" name="weight" id="weight" min="1" max="20" autocomplete="off" placeholder="Input weight" value="1" required> <br>
                                ₱<label id="order_price_label">0</label>
                                <input type="hidden" id="order_price" name="order_price" value="">
                                <button type="submit" name="btn">Submit</button>
                            </form>
                        </div>
                </div>

                <div class="list">
                    <div class="card1">
                        <div class="card1-body"></div>
                                <table>
                                    <tr>
                                        <th>Order Number</th>
                                        <th style="background: green;">Complete</th>
                                    </tr>
                                    <?php
                                    if($select_query_result_all_complete_orders) {
                                        foreach($select_query_result_all_complete_orders as $key => $value) {
                                            echo '<tr>';
                                            foreach($value as $k => $v) {
                                                echo '<td>'.$v.'</td>';
                                            }
                                            echo '</tr>';
                                        }
                                    }
                                    ?>
                                </table>
                            
                      </div>
                      <div class="card2">
                        <div class="card2-body"></div>
                        <table>
                            <tr>
                                <th>Order Number</th>
                                <th style="background-color: #43A6ED;">In-Progress</th>
                            </tr>
                            <?php
                                if($select_query_result_all_inprogress_orders) {
                                    foreach($select_query_result_all_inprogress_orders as $key => $value) {
                                        echo '<tr>';
                                        foreach($value as $k => $v) {
                                            echo '<td>'.$v.'</td>';
                                        }
                                        echo '</tr>';
                                    }
                                }
                            ?>
                        </table>
                      </div>
                      <div class="card3">
                        <div class="card3-body"></div>
                        <table>
                            <tr>
                                <th>Order Number</th>
                                <th style="background: rgb(255,37,0);">Pending</th>
                            </tr>
                            <?php
                                if($select_query_result_all_pending_orders) {
                                    foreach($select_query_result_all_pending_orders as $key => $value) {
                                        echo '<tr>';
                                        foreach($value as $k => $v) {
                                            echo '<td>'.$v.'</td>';
                                        }
                                        echo '</tr>';
                                    }
                                }
                            ?>
                        </table>
                      </div>
                </div>
            </div>
            <div class="second-container">
                
                    <div class="my-order-inner">
                        <h3>My Orders</h4>
                        <form>
                            <input type="text" name="user-order" id="user-order" autocomplete="off" placeholder="SEARCH ORDER"><br>
                            <button name="btn">Submit</button>
                        </form>
                    </div>
                    <div class="my-order-body">
                        <table>
                            <tr>
                                <th>Order Number</th>
                                <th>Date</th>
                                <th>Type of Service</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </tr>
                            <?php
                            if(isset($select_query_result_orders)) {
                                foreach($select_query_result_orders as $key => $value) {
                                    echo '<tr>';
                                    foreach($value as $k => $v) {
                                        echo '<td>'.$v.'</td>';
                                    }
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
        @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap");
            *{
                margin: 0;
                padding: 0;
                border: none;
                outline: none;
                text-decoration: none;
                box-sizing: border-box;
                font-family: "Poppins", sans-serif;
            }
    
    
        body{
                /* background: rgb(219, 219, 219); */
                background: rgb(245,245,245);
                
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
            }
        .logo{
                width: 13%;
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
        .side-bar form {
            width: 100%;
            padding: 1.8rem 3rem;
            font-weight: 500;
            font-size: 18px;
            color: black;
        
        }
    
        .side-bar a:hover{
            background: #43A6ED;
            color: white;
            border-radius: 20px;
        
        }
        .side-bar form input:hover{
            background: #43A6ED;
            color: white;
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
        button{
            height: 30px;
            width: 20%;
            font-size: 15px;
            background-color: #43A6ED;
            margin: 5px;
            border: 0;
            border-radius: 5px;
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
    <script src="js_scripts/script.js"></script>
</body>
</html>
    
