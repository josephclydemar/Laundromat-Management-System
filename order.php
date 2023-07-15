<?php
include_once "database_connection.php";
include_once "payment.php";
    class Order
    {
        private $order_id;
        private $user_id;
        private $service_id;
        private $date_ordered ;
        private $remaining_time;
        private $order_status;
        private $order_weight;
        private $order_description;

        private $db_connection;
        private $pdo;

        public function __construct($user_id, $service_id, $date_ordered , $remaining_time, $order_status, $order_weight, $order_description, $order_price)
        {
            $this->user_id = $user_id;
            $this->service_id = $service_id;
            $this->date_ordered  = $date_ordered ;
            $this->remaining_time = $remaining_time;
            $this->order_status = $order_status;
            $this->order_weight = $order_weight;
            $this->order_description = $order_description;
            
            $this->db_connection = new DatabaseConnection();
            $this->pdo = $this->db_connection->getPDO();

            $this->createOrder();
            $sql_select = "SELECT * FROM orders ORDER BY order_id DESC";
            $stmt = $this->pdo->query($sql_select);
            $last_order = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->order_id = $last_order['order_id'];

            new Payment($this->order_id, $this->date_ordered, $order_price);
        }

        public function createOrder()
        {
            $sql_insert = "INSERT INTO orders (user_id, service_id, date_ordered, remaining_time, order_status, order_weight, order_description) VALUES (:user_id, :service_id, :date_ordered , :remaining_time, :order_status, :order_weight, :order_description)";
            $stmt = $this->pdo->prepare($sql_insert);
            $stmt->execute(array(
                                 ':user_id'=>$this->user_id,
                                 ':service_id'=>$this->service_id,
                                 ':date_ordered'=>$this->date_ordered,
                                 ':remaining_time'=>$this->remaining_time,
                                 ':order_status'=>$this->order_status,
                                 ':order_weight'=>$this->order_weight,
                                 ':order_description'=>$this->order_description
                                ));
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