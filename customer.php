<?php
include_once "order.php";
include_once "database_connection.php";

    class Customer
    {
        private $user_id;
        private $firstname;
        private $lastname;
        private $user_address;
        private $email;
        private $user_password;
        private $total_orders = 0;

        private $db_connection;
        private $pdo;

        public function __construct($firstname, $lastname, $user_address, $email, $user_password)
        {
            $this->firstname = $firstname;
            $this->lastname = $lastname;
            $this->user_address = $user_address;
            $this->email = $email;
            $this->user_password = $user_password;

            $this->db_connection = new DatabaseConnection();
            $this->pdo = $this->db_connection->getPDO();

            $sql_select = "SELECT * FROM users WHERE emaiL=:email";
	        $stmt = $this->pdo->prepare($sql_select);
            $stmt->execute(array(':email'=>$email));
	        $select_user_result = $stmt->fetch(PDO::FETCH_ASSOC);

            if(!$select_user_result)
            {
                $this->createUser($firstname, $lastname, $user_address, $email, $user_password);
                $sql_select = "SELECT * FROM users ORDER BY user_id DESC";
                $stmt = $this->pdo->query($sql_select);
                $last_user = $stmt->fetch(PDO::FETCH_ASSOC);
                $this->user_id = $last_user['user_id'];
            }
            else {
                $this->user_id = $select_user_result['user_id'];
            }
        }

        public function createUser()
        {
            $total_orders = 0;
            $insert_new_user = "INSERT INTO users (firstname, lastname, user_address, total_orders, email, user_password) VALUES (:firstname, :lastname, :user_address, :total_orders, :email, :user_password)";
            $stmt = $this->pdo->prepare($insert_new_user);
            $stmt->execute(array(':firstname'=>$this->firstname,
                                 ':lastname'=>$this->lastname,
                                 ':user_address'=>$this->user_address,
                                 ':total_orders'=>$this->total_orders,
                                 ':email'=>$this->email,
                                 ':user_password'=>$this->user_password
                                ));
        }

        public function getUserId()
        {
            return $this->user_id;
        }

        public function getMyInfo()
        {
            $sql_select = "SELECT * FROM users WHERE user_id=:user_id";
            $stmt = $this->pdo->prepare($sql_select);
            $stmt->execute(array(':user_id'=>$this->user_id));
            $user_query_result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $user_query_result;
        }

        public function createOrder($service_type, $date_ordered, $remaining_time, $order_status, $order_weight, $order_description, $order_price)
        {
            new Order($this->getUserId(), $service_type, $date_ordered, $remaining_time, $order_status, $order_weight, $order_description, $order_price);
        }

        public function getMyOrders()
        {
            // get all user orders
            $sql_select = "SELECT order_id, date_ordered, service_id, order_weight, order_status FROM orders WHERE user_id=:user_id";
	        $stmt = $this->pdo->prepare($sql_select);
            $stmt->execute(array(':user_id'=>$this->user_id));
	        $select_orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
            // var_dump($select_query_result_orders);
            // echo $select_query_result_orders;
            return $select_orders;
        }
    }
?>