<?php
    session_start();
    include_once "database_connection.php";
    $db_conn = new DatabaseConnection();
    $pdo = $db_conn->getPDO();

    if(isset($_SESSION['user_id'])) {
        // echo $_SESSION['user_id'];
        $sql_select = "SELECT * FROM users WHERE user_id=:user_id";
	    $stmt = $pdo->prepare($sql_select);
        $stmt->execute(array(':user_id'=>$_SESSION['user_id']));
	    $select_query_result = $stmt->fetch(PDO::FETCH_ASSOC);
        foreach($select_query_result as $key => $value) {
            echo $key.' : '.$value.'<br>';
        }
    }
?>