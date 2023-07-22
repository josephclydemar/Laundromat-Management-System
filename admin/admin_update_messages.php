<?php
    include_once "../classes/database_connection.php";

    $db_conn = new DatabaseConnection();
    $pdo = $db_conn->getPDO();

    if( isset($_POST['feedback_message_update']) )
    {
        $message_order_id_and_user_id = explode('-', $_POST['feedback_message_update']);

        $sql_select_messages = "SELECT * FROM messages WHERE order_id=:order_id ORDER BY message_id DESC";
	    $stmt = $pdo->prepare($sql_select_messages);
        $stmt->execute(array(
                             ':order_id'=>$message_order_id_and_user_id[0]
                            ));
	    $record = $stmt->fetch(PDO::FETCH_ASSOC);

        if($record)
        {
            if($record['user_id'] != $message_order_id_and_user_id[1])
            {
                echo $record['message'];
            }
            else
            {
                echo '';
            }
        }
        else
        {
            echo '';
        }
    }
?>