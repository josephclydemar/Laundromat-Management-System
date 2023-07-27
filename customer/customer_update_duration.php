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
            // echo $key;
            if($updated_remaining_time <= 0)
            {
                $sql_update_order_status_to_complete = "UPDATE orders SET order_status=:order_status WHERE order_id=:order_id";
                $stmt = $pdo->prepare($sql_update_order_status_to_complete);
                $stmt->execute(array(
                                     ':order_status'=>1,
                                     ':order_id'=>$record['order_id']
                                    ));

                
                                
                // $sql_select_pending_orders = "SELECT * FROM orders WHERE user_id=:user_id AND order_status=:order_status";
                // $stmt = $pdo->prepare($sql_select_pending_orders);
                // $stmt->execute(array(
                //                      ':user_id'=>$split_key[3],
                //                      ':order_status'=>3
                //                     ));
                // $pending_record = $stmt->fetch(PDO::FETCH_ASSOC);
                // // var_dump($pending_record);

                // if($pending_record)
                // {
                //     $date_set_to_inprogress = date('Y-m-d H:i:s');
                //     $splitted_date_set_to_inprogress_date_time = explode(' ', $date_set_to_inprogress);
                //     $splitted_date_set_to_inprogress_by_hour_minute_second = explode(':', $splitted_date_set_to_inprogress_date_time[1]);
                //     $calculated_minute = (((int)$splitted_date_set_to_inprogress_by_hour_minute_second[1]) + ((int)$pending_record['remaining_time']) / 60);
                //     $date_set_to_finish = $splitted_date_set_to_inprogress_date_time[0].' '.$splitted_date_set_to_inprogress_by_hour_minute_second[0].':'.$calculated_minute.':'.$splitted_date_set_to_inprogress_by_hour_minute_second[2];
                    
                //     // echo $date_set_to_inprogress.' pppe '.$date_set_to_finish;
                //     // echo 'HAHA '.$splitted_date_set_to_inprogress_by_hour_minute_second[1];
                //     // echo 'HAHA '.$pending_record['remaining_time'];
    
                //     $sql_update_order_status_to_inprogress = "UPDATE orders SET date_ordered=:date_ordered, date_finish=:date_finish, order_status=:order_status WHERE order_id=:order_id";
                //     $stmt = $pdo->prepare($sql_update_order_status_to_inprogress);
                //     $stmt->execute(array(
                //                          ':date_ordered'=>$date_set_to_inprogress,
                //                          ':date_finish'=>$date_set_to_finish,
                //                          ':order_status'=>2,
                //                          ':order_id'=>$pending_record['order_id']
                //                         ));
                // }
                
                // header('Location: customer_dashboard.php');
                // return;
            }
            // else
            // {
            //     // echo $key;
            //     $record['remaining_time'];
            // }
        }
    }
    
?>