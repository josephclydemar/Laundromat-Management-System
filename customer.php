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
            
            $this->createuser($firstname, $lastname, $user_address, $email, $user_password);
            $sql_select = "SELECT * FROM users ORDER BY user_id DESC";
            $stmt = $this->pdo->query($sql_select);
            $last_user = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->user_id = $last_user['user_id'];
        }

        public function createuser()
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

        public function getuserId()
        {
            return $this->user_id;
        }

        public function getuserInfo()
        {
            $sql_select = "SELECT * FROM users WHERE user_id=:user_id";
            $stmt = $this->pdo->prepare($sql_select);
            $stmt->execute(array(':user_id'=>$this->user_id));
            $userQueryResult = $stmt->fetch(PDO::FETCH_ASSOC);
            return $userQueryResult;
        }

        public function createOnlineOrder() // Still Unsure
        {
            $service_type = $_POST['service_type'];
            $laundry_weight = $_POST['laundry_weight'];
            $price = $_POST['price'];
            new Order($this->getuserId(), $service_type, $laundry_weight, $price);
            $this->total_orders += 1;
        }
    }
?>