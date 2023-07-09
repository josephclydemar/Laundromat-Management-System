<?php
    session_start();
    include_once "customer.php";
    // echo 'username: '.$_POST['username'].'<br>';
    // echo 'password: '.$_POST['password'].'<br>';
    if(isset($_POST)) {
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $customer_address = $_POST['customer_address'];
        $email = $_POST['email'];
        $customer_password = $_POST['customer_password'];
        // echo $firstname.' '. $lastname.' '. $customer_address.' '. $email.' '.$customer_password;
        $new_customer = new Customer($firstname, $lastname, $customer_address, $email, $customer_password);
        $customer_id = $new_customer->getCustomerId();

        $_SESSION['customer_id'] = $customer_id;
        header('Location: customer_track_orders.php');
        return;
    }
?>