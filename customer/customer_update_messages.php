<?php
    include_once "../classes/database_connection.php";

    $db_conn = new DatabaseConnection();
    $pdo = $db_conn->getPDO();

    function updateMessageStateToRead($order_id, $user_id, $pdo_object)
    {
        $been_read = 1;
        $sql_update_message = "UPDATE messages SET been_read=:been_read WHERE order_id=:order_id AND user_id=:user_id";
	    $stmt = $pdo_object->prepare($sql_update_message);
        $stmt->execute(array(
                             ':been_read'=>$been_read,
                             ':order_id'=>$order_id,
                             ':user_id'=>$user_id
                            ));
    }

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
                updateMessageStateToRead($record['order_id'], $record['user_id'], $pdo);
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