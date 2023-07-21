<?php
    include_once "../database_connection.php";
    $db_conn = new DatabaseConnection();
    $pdo = $db_conn->getPDO();
    for($i = 0; $i < 5000; $i++)
    {
        $firstname = 'Remy'.$i;
        $lastname = 'Baldomero'.$i;
        $user_address = 'Venus';
        $total_orders = $i;
        $email = 'ronny'.$i.'@gmail.com';
        $user_password = 'macho'.$i;
        $insert_new_user = "INSERT INTO users (firstname, lastname, user_address, total_orders, email, user_password) VALUES (:firstname, :lastname, :user_address, :total_orders, :email, :user_password)";
        $stmt = $pdo->prepare($insert_new_user);
        $stmt->execute(array(':firstname'=>$firstname,
                             ':lastname'=>$lastname,
                             ':user_address'=>$user_address,
                             ':total_orders'=>$total_orders,
                             ':email'=>$email,
                             ':user_password'=>$user_password
                            ));
    }
    
?>