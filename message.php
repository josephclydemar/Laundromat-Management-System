<?php
    include_once "database_connection.php";
    class Message
    {
        private $message_id;
        private $user_id;
        private $order_id;
        private $message_date;
        private $message;

        private $db_connection;
        private $pdo;

        public function __construct($user_id, $order_id, $message_date, $message)
        {
            $this->user_id = $user_id;
            $this->order_id = $order_id;
            $this->message_date = $message_date;
            $this->message = $message;

            $this->db_connection = new DatabaseConnection();
            $this->pdo = $this->db_connection->getPDO();

            $sql_select = "SELECT * FROM messages WHERE user_id=:user_id AND order_id=:order_id AND message=:message";
	        $stmt = $this->pdo->prepare($sql_select);
            $stmt->execute(array(
                                 ':user_id'=>$this->user_id,
                                 ':order_id'=>$this->order_id,
                                 ':message'=>$this->message
                                ));
	        $select_message_result = $stmt->fetch(PDO::FETCH_ASSOC);

            if(!$select_message_result)
            {
                $this->createMessage($this->user_id, $this->order_id, $this->message_date, $this->message, );
                $sql_select = "SELECT * FROM messages ORDER BY message_id DESC";
                $stmt = $this->pdo->query($sql_select);
                $last_message = $stmt->fetch(PDO::FETCH_ASSOC);
                $this->message_id = $last_message['message_id'];
            }
            else {
                $this->message_id = $select_message_result['message_id'];
            }
        }

        public function createMessage()
        {
            $insert_new_message = "INSERT INTO messages (user_id, order_id, message_date, message) VALUES (:user_id, :order_id, :message_date, :message)";
            $stmt = $this->pdo->prepare($insert_new_message);
            $stmt->execute(array(':user_id'=>$this->user_id,
                                 ':order_id'=>$this->order_id,
                                 ':message_date'=>$this->message_date,
                                 ':message'=>$this->message
                                ));
        }
    }
?>