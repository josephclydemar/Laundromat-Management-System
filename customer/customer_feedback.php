<?php
    session_start();
    include_once "../classes/database_connection.php";
    include_once "customer.php";
    // include_once "message.php";
    date_default_timezone_set('Asia/Singapore');

    $db_conn = new DatabaseConnection();
    $pdo = $db_conn->getPDO();
    if(isset($_GET['back']))
    {
        header('Location: customer_dashboard.php');
        return;
    }


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
        // echo '<h1>CUSTOMER SIDE</h1>';
        // echo $user_info['user_id'].'<br>';
        // echo $user_info['firstname'].'<br>';
        // echo $user_info['lastname'].'<br>';
        // echo $user_info['email'].'<br>';

        if(isset($_POST['message']) && isset($_POST['message_button']))
        {
            $date_today = date('Y-m-d H:i:s');
            $customer->createMessage($_SESSION['order_id'], $date_today, $_POST['message']);
            header('Location: customer_feedback.php');
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
    <link rel="icon" type="image/x-icon" href="imgs/washing-machine_icon-icons.com_60734.ico">
</head>
<body>
    <header class="header">
        <div class="logo">
            Wash Matic
        </div>

        <div class="dashboard">
            Customer Side Feedback
        </div>

        <div class="user">
            <?php
            echo '<span style="font-weight: 900;">'.$user_info['firstname'].' '.$user_info['lastname'].'</span><br>';
            echo '<span style="font-weight: 900;"><span style="font-size:13px;">USER ID: '.$user_info['user_id'].'</span>';
            ?>
        </div>
    </header>
    <div class="container">
        <div class="side-bar">
        <form method="GET">
              <input type="submit" name="back" value="<< BACK">
        </form>
        </div>
        <div id="main_container">
        
        <?php
            echo '<div style="font-weight: 900;">Admin</div>';
            echo '<div style="font-weight: 900;">Order ID: '.$_SESSION['order_id'].'</div>';
            echo '<div id="get_order_id" hidden>'.$_SESSION['order_id'].'</div><div id="get_user_id" hidden>'.$_SESSION['user_id'].'</div>';
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
                    echo '<div class="user_type_'.$user_info['user_type'].'" style="font-size: 13px;">'.$value['message'].'</div>';
                }
            }
            ?>
            </div>
            <form method="POST">
                <input class="chat_input" id="chat_text" type="text" name="message" required>
                <input class="chat_input" id="chat_send" type="submit" name="message_button" value="Send">
            </form>
        </div>

    </div>
    
    <style>
        /* CSS styles go here */


        
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
        #messages {
            overflow-y: scroll;
            height: 300px;
            width: 500px;
        }
        .user_type_1 {
            text-align: left;
            background-color: #002244;
            color: #fff;
            padding-top: 10px;
            padding-bottom: 10px;
            padding-left: 20px;
            padding-right: 20px;
            margin-top: 4px;
            margin-bottom: 4px;
            border-radius: 10px;
            
        }
        .user_type_0 {
            text-align: right;
            background-color: dodgerblue;
            color: #fff;
            padding-top: 10px;
            padding-bottom: 10px;
            padding-left: 20px;
            padding-right: 20px;
            margin-top: 4px;
            margin-bottom: 4px;
            border-radius: 10px;
        }
        .chat_input {
            padding-top: 10px;
            padding-bottom: 10px;
            border-radius: 10px;
            margin-top: 20px;
        }
        #chat_text {
            width: 400px;
            padding-left: 20px;
            padding-right: 20px;
            border: 1px solid #000;
        }
        #chat_send {
            font-weight: 800;
            padding-left: 30px;
            padding-right: 30px;
            border: 1px solid #000;
        }
        #main_container {
            margin: auto;
            margin-top: 20px;
            background-color: #fff;
            padding-top: 30px;
            padding-bottom: 30px;
            padding-left: 30px;
            padding-right: 30px;
            border-radius: 20px;
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
                padding-top: 5px;
                font-size: 15px;
                text-align: left;
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

    .side-bar a:hover{
        background: #fff;
        color: #43A6ED;
        border-radius: 20px;
    
    }
    

    .head h2{
        color: white;
        padding: 15px;
    }



    </style>
    <script src="js_scripts/customer_messages_script.js"></script>
</body>
</html>
