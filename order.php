<?php
    class Order
    {
        private $order_id;
        private $customer_id;
        private $service_type;
        private $laundry_weight;
        private $price;

        private $db_username = 'root';
        private $db_password = '';
        private $db_name = 'laundry_system';
        private $host = 'localhost';
        private $port = '3306';

        private $dsn;
        private $pdo;

        public function __construct($customer_id, $service_type, $laundry_weight, $price)
        {
            $this->customer_id = $customer_id;
            $this->service_type = $service_type;
            $this->laundry_weight = $laundry_weight;
            $this->price = $price;
            $this->dsn = 'mysql:host='.$this->host.';port='.$this->port.';dbname='.$this->db_name;
            $this->pdo = new PDO($this->dsn, $this->db_username, $this->db_password);

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