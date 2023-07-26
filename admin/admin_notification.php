<?php
    include_once "../classes/database_connection.php";

    $db_conn = new DatabaseConnection();
    $pdo = $db_conn->getPDO();

    foreach($_POST as $key => $value)
    {
        if(isset($_POST[$key]))
        {
            $been_read = 1;
            $split_key = explode('_', $key);
            $sql_select_orders = "SELECT * FROM messages WHERE order_id=:order_id AND user_id=:user_id AND NOT been_read=:been_read";
	        $stmt = $pdo->prepare($sql_select_orders);
            $stmt->execute(array(
                                 ':order_id'=>$split_key[1],
                                 ':user_id'=>$split_key[2],
                                 ':been_read'=>$been_read
                                ));
	        $all_messages_of_customer_in_the_order = $stmt->fetch(PDO::FETCH_ASSOC);
            $has_been_read = false;
            $ha = 'je';
            // foreach($all_messages_of_customer_in_the_order as $record)
            // {
            //     if($record['been_read'] == 1)
            //     {
            //         $ha = $record['been_read'];
            //         $has_been_read = true;
            //         break;
            //     }
            // }
            // if($has_been_read == true)
            // {
            //     echo 'has_been_read';
            // }else{
            //     echo 'WALA';
            // }
            if($all_messages_of_customer_in_the_order)
            {
                // echo $all_messages_of_customer_in_the_order['message'];
                echo 'NAA_notification_'.$all_messages_of_customer_in_the_order['order_id'].'_'.$all_messages_of_customer_in_the_order['user_id'];
            }
            else
            {
                echo 'WALA_'.$key;
            }
            
        }
    }

?>