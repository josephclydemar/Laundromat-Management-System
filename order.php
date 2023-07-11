<?php
include_once "database_connection.php";
    class Order
    {
        private $order_id;
        private $customer_id;
        private $service_type;
        private $laundry_weight;
        private $price;

        private $db_connection;
        private $pdo;

        public function __construct($customer_id, $service_type, $laundry_weight, $price)
        {
            $this->customer_id = $customer_id;
            $this->service_type = $service_type;
            $this->laundry_weight = $laundry_weight;
            $this->price = $price;
            
            $this->db_connection = new DatabaseConnection();
            $this->pdo = $this->db_connection->getPDO();

            $this->createOrder();
            $sql_select = "SELECT * FROM orders ORDER BY order_id DESC";
            $stmt = $this->pdo->query($sql_select);
            $last_order = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->order_id = $last_order['order_id'];
        }

        public function createOrder()
        {
            $sql_insert = "INSERT INTO orders (customer_id, service_type, laundry_weight, price) VALUES (:customer_id, :service_type, :laundry_weight, :price)";
            $stmt = $this->pdo->prepare($sql_insert);
            $stmt->execute(array(':customer_id'=>$this->customer_id, ':service_type'=>$this->service_type, ':laundry_weight,'=>$this->laundry_weight, ':price'=>$this->price));
        }

        public function getOrderId()
        {
            return $this->order_id;
        }

        public function getOrderInfo()
        {
            $sql_select = "SELECT * FROM orders WHERE order_id=:order_id";
            $stmt = $this->pdo->prepare($sql_select);
            $stmt->execute(array(':order_id'=>$this->order_id));
            $order_query_result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $order_query_result;
        }
    }
?>