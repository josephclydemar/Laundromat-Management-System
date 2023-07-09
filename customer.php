<?php
include_once "order.php";
include_once "database_connection.php";

    class Customer
    {
        private $customer_id;
        private $firstname;
        private $lastname;
        private $customer_address;
        private $email;
        private $customer_password;
        private $total_orders = 0;

        private $db_connection;
        private $pdo;

        public function __construct($firstname, $lastname, $customer_address, $email, $customer_password)
        {
            $this->firstname = $firstname;
            $this->lastname = $lastname;
            $this->customer_address = $customer_address;
            $this->email = $email;
            $this->customer_password = $customer_password;

            $this->db_connection = new DatabaseConnection();
            $this->pdo = $this->db_connection->getPDO();
            
            $this->createCustomer($firstname, $lastname, $customer_address, $email, $customer_password);
            $sql_select = "SELECT * FROM customers ORDER BY customer_id DESC";
            $stmt = $this->pdo->query($sql_select);
            $last_customer = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->customer_id = $last_customer['customer_id'];
        }

        public function createCustomer()
        {
            $total_orders = 0;
            $insert_new_customer = "INSERT INTO customers (firstname, lastname, customer_address, total_orders, email, customer_password) VALUES (:firstname, :lastname, :customer_address, :total_orders, :email, :customer_password)";
            $stmt = $this->pdo->prepare($insert_new_customer);
            $stmt->execute(array(':firstname'=>$this->firstname,
                                 ':lastname'=>$this->lastname,
                                 ':customer_address'=>$this->customer_address,
                                 ':total_orders'=>$this->total_orders,
                                 ':email'=>$this->email,
                                 ':customer_password'=>$this->customer_password
                                ));
        }

        public function getCustomerId()
        {
            return $this->customer_id;
        }

        public function getCustomerInfo()
        {
            $sql_select = "SELECT * FROM customers WHERE customer_id=:customer_id";
            $stmt = $this->pdo->prepare($sql_select);
            $stmt->execute(array(':customer_id'=>$this->customer_id));
            $customerQueryResult = $stmt->fetch(PDO::FETCH_ASSOC);
            return $customerQueryResult;
        }

        public function createOnlineOrder() // Still Unsure
        {
            $service_type = $_POST['service_type'];
            $laundry_weight = $_POST['laundry_weight'];
            $price = $_POST['price'];
            new Order($this->getCustomerId(), $service_type, $laundry_weight, $price);
            $this->total_orders += 1;
        }
    }
?>