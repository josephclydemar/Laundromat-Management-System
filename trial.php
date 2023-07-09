<?php
    $db_username = 'root';
    $db_password = '';
    $db_name = 'suway_lang';
    $host = 'localhost';
    $port = '3306';

    $dsn = 'mysql:host='.$host.';port='.$port.';dbname='.$db_name;
    $pdo = new PDO($dsn, $db_username, $db_password);

    // $sqlSelect = "SELECT * FROM restaurants WHERE restaurant_id=:restaurant_id";
	// $stmt = $this->pdo->prepare($sqlSelect);
	// $stmt->execute(array(':restaurant_id'=>$this->restoId));
	// $restoQueryResult = $stmt->fetch(PDO::FETCH_ASSOC);

    // $sqlGetPhotos = "SELECT * FROM resto_photos WHERE restaurant_id_photos=:resto_id";
	// $stmt = $this->pdo->prepare($sqlGetPhotos);
	// $stmt->execute(array(':resto_id'=>$this->restoId));
	// $restoQueryResult= $stmt->fetchAll(PDO::FETCH_ASSOC);

    // $sqlSelect_r = "SELECT * FROM restaurants";
	// $stmt = $this->pdo->query($sqlSelect_r);
	// $userQueryResult_r = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // $sendMessageInsert = "INSERT INTO messages_table(thread_id, text_message, msg_user_type) VALUES (:thread_id, :text_message, :user_type)";
	// $stmt = $this->pdo->prepare($sendMessageInsert);
	// $stmt->execute(array(':thread_id'=>$threadId, ':text_message'=>$message, ':user_type'=>$userInfo['user_type']));


    if(isset($_POST['lat'])) {
        if($_POST['surname'] != '' && $_POST['firstname'] != '' && $_POST['age'] != '') {
            $sqlInsert = "INSERT INTO users (surname, firstname, age) VALUES (:surname, :firstname, :age)";
            $stmt = $pdo->prepare($sqlInsert);
            $userData = array(':surname'=>$_POST['surname'], ':firstname'=>$_POST['firstname'], ':age'=>$_POST['age']);
            $stmt->execute($userData);
        }

        // $sqlSelect = "SELECT * FROM users";
        // $stmt = $pdo->query($sqlSelect);
        // // $allUsers = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // $lastUser = $stmt->fetch(PDO::FETCH_ASSOC);
        // var_dump($allUsers);

        $sqlSelect = "SELECT * FROM users ORDER BY user_id DESC";
        $stmt = $pdo->query($sqlSelect);
        // $allUsers = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $lastUser = $stmt->fetch(PDO::FETCH_ASSOC);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>TEST</h1>
    <?php
        // if(isset($allUsers)) {
        //     foreach($allUsers as $key => $value) {
        //         // print_r($value);
        //         // echo '<h4>';
        //         foreach($value as $k => $v) {
        //             echo ' : '.$v;
        //         }
        //         echo '<br>';
        //         // echo '<h4>';
        //     }
        // }
        if(isset($lastUser)) {
            foreach($lastUser as $k => $v) {
                echo $k.' : '.$v.'<br>';
            }
        }
    ?>
    <form method="POST">
        <input type="text" name="surname" placeholder="Familiy Name">
        <input type="text" name="firstname" placeholder="Given Name">
        <input type="number" name="age" placeholder="Age">
        <input type="submit" name="lat" value="LATEST">
    </form>
</body>
</html>