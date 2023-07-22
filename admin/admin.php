<?php
    include_once "../classes/database_connection.php";

    class Admin
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

        public function getMyInfo()
        {
            $sql_select = "SELECT * FROM users WHERE user_id=:user_id";
            $stmt = $this->pdo->prepare($sql_select);
            $stmt->execute(array(':user_id'=>$this->user_id));
            $user_query_result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $user_query_result;
        }

        public function getMyCustomers()
        {
            $select_all_customers = "SELECT * FROM users WHERE user_type=:user_type";
            $stmt = $this->pdo->prepare($select_all_customers);
            $stmt->execute(array(':user_type'=>0));
            $select_customers_result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $select_customers_result;
        }

        public function getCustomerOrders($customer_id)
        {
            $select_all_customers = "SELECT * FROM orders WHERE user_id=:user_id";
            $stmt = $this->pdo->prepare($select_all_customers);
            $stmt->execute(array(':user_id'=>$customer_id));
            $select_customers_result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $select_customers_result;
        }

        public function createMessage($order_id, $message_date, $message)
        {
            $insert_new_message = "INSERT INTO messages (user_id, order_id, message_date, message) VALUES (:user_id, :order_id, :message_date, :message)";
            $stmt = $this->pdo->prepare($insert_new_message);
            $stmt->execute(array(
                                 ':user_id'=>$this->user_id,
                                 ':order_id'=>$order_id,
                                 ':message_date'=>$message_date,
                                 ':message'=>$message
                                ));
        }

        public function getOrderMessages($order_id)
        {

            $select_all_customer_orders = "SELECT * FROM messages WHERE order_id=:order_id";
            $stmt = $this->pdo->prepare($select_all_customer_orders);
            $stmt->execute(array(
                                 ':order_id'=>$order_id
                                ));
            $select_customer_orders_result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $select_customer_orders_result;
        }
    }
?>