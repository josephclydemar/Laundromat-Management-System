<?php
    include_once "../classes/database_connection.php";

    $db_conn = new DatabaseConnection();
    $pdo = $db_conn->getPDO();

    // echo $_POST['rem_time177'];
    // var_dump($_POST);
    // foreach($_POST as $key => $value)
    // {
    //     var_dump($key);
    // }
    foreach($_POST as $key => $value)
    {
        if(isset($_POST[$key]))
        {
            // echo $key[1];
            $split_key = explode('_', $key);
            // echo $split_key[2];
            $sql_select_orders = "SELECT * FROM orders WHERE order_id=:order_id";
	        $stmt = $pdo->prepare($sql_select_orders);
            $stmt->execute(array(':order_id'=>$split_key[2]));
	        $record = $stmt->fetch(PDO::FETCH_ASSOC);
    
            $time_today_for_strtotime = date('d-m-Y H:i:s');
            $time_now = strtotime($time_today_for_strtotime);
            $date_finish_database_split = explode(' ', $record['date_finish']);
            $date_finish_split = explode('-', $date_finish_database_split[0]);
            $date_finish_for_strtotime = $date_finish_split[2].'-'.$date_finish_split[1].'-'.$date_finish_split[0].' '.$date_finish_database_split[1];
            $time_to_finish = strtotime($date_finish_for_strtotime);
    
            $updated_remaining_time = $time_to_finish - $time_now;
            $sql_update_order_remaining_time = "UPDATE orders SET remaining_time=:remaining_time WHERE order_id=:order_id";
            $stmt = $pdo->prepare($sql_update_order_remaining_time);
            $stmt->execute(array(
                                 ':remaining_time'=>$updated_remaining_time,
                                 ':order_id'=>$record['order_id']
                                ));
            echo $record['remaining_time'];
            if($updated_remaining_time <= 0)
            {
                $sql_update_order_status = "UPDATE orders SET order_status=:order_status WHERE order_id=:order_id";
                $stmt = $pdo->prepare($sql_update_order_status);
                $stmt->execute(array(
                                     ':order_status'=>1,
                                     ':order_id'=>$record['order_id']
                                    ));
                // header('Location: customer_dashboard.php');
                // return;
            }
        }
    }
    
?>