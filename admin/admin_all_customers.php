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

        if(isset($_POST['back']))
        {
            header('Location: admin_dashboard.php');
            return;
        }

        $admin = new Admin($user_info['firstname'], $user_info['lastname'], $user_info['user_address'], $user_info['email'], $user_info['user_password']);
        $my_info = $admin->getMyInfo();
        // echo '<h1>ADMIN SIDE</h1>';
        // echo $my_info['user_id'].'<br>';
        // echo $my_info['firstname'].'<br>';
        // echo $my_info['lastname'].'<br>';
        // echo $my_info['email'].'<br>';
        // echo '<br>';

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
    <link rel="icon" type="image/x-icon" href="imgs/washing-machine_icon-icons.com_60734.ico">
</head>
<body>
    <header class="header">
        <div class="logo">
            Wash Matic
        </div>

        <div class="dashboard">
            All Customers
        </div>

        <div class="user">
        <?php
            echo '<span style="font-weight: bold;">'.$my_info['firstname'].' '.$my_info['lastname'].'</span>';
            echo '<br>';
            echo '<span style="font-weight: bold;">'.$my_info['user_id'].'</span>';
            ?>
        </div>
    </header>
    <div class="container">
        <div class="side-bar">

        <form method="POST">
            <input class="side_tabs" type="submit" name="back" value="Back to Dashboard">
        </form>
            <div class="logout">
            <form method="GET">
                <input type="submit" name="logout" value="Log-out" class="logout">
            </form>
            </div>
        </div>
        <div class="main-container">
            <div class="second-container">
                
                    <div class="my-order-inner">
                        <h3>List of all Customers</h4>
                        
                    </div>
                    <div class="my-order-body">
                        <table>
                            <tr>
                                <th>Customer ID</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Email</th>
                                <th>Customer Order</th>
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
                        echo '<td><form method="POST"><input type="hidden" name="customer_id" value="'.$record['user_id'].'"><input type="hidden" name="admin_id" value="'.$my_info['user_id'].'"><input class="see_order_button" type="submit" name="customer_orders_button" value="See Orders"></form></td>';
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

    .container .side-bar .logout input{
        padding: 1.8rem 3rem;
        width: 100%;
        font-weight: 500;
        font-size: 18px;
        color: black;
        background: #43A6ED;
    }

    .container .side-bar .logout input:hover{
        background: #fff;
        color: #43A6ED;
        border-radius: 20px;
    }

    .see_order_button {
        height: 30px;
        width: 50%;
        font-size: 15px;
        background: #43A6ED;
        margin: 5px;
        border: 0;
        border-radius: 5px;
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

    .side_tabs {
        width: 100%;
        /* padding: 1.8rem 3rem;
         */
        padding: 35px 10px 35px 10px;
        font-weight: 500;
        font-size: 18px;
        color: black; 
        background: #43A6ED;   
    }

    .side_tabs:hover{
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

    .second-container{
        width: 100%;
        height: 90%;
    }
    
    

    .second-container .my-order-body table{
        width: 100%;
    }

    .second-container .my-order-body{
        width: 100%;
        padding: 20px;
        height: 80%;
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

    .second-container .my-order-body th{
        background: #43A6ED;
        color: white;
        padding: 10px;
    }
  






    </style>
</body>
</html>
