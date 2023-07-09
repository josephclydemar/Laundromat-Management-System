<?php
    session_start();
    include_once "database_connection.php";
    $db_conn = new DatabaseConnection();
    $pdo = $db_conn->getPDO();

    if(isset($_SESSION['customer_id'])) {
        // echo $_SESSION['customer_id'];
        $sql_select = "SELECT * FROM customers WHERE customer_id=:customer_id";
	    $stmt = $pdo->prepare($sql_select);
        $stmt->execute(array(':customer_id'=>$_SESSION['customer_id']));
	    $resto_query_result = $stmt->fetch(PDO::FETCH_ASSOC);
        foreach($resto_query_result as $key => $value) {
            echo $key.' : '.$value.'<br>';
        }
    }
?>