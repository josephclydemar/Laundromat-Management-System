<?php
include_once "database_connection.php";
class Payment {
    private $payment_id;
    private $order_id;
    private $payment_date;
    private $payment_amount;

    private $db_connection;
    private $pdo;
    
    public function __construct($order_id, $payment_date, $payment_amount)
    {
        $this->order_id = $order_id;
        $this->payment_date = $payment_date;
        $this->payment_amount = $payment_amount;

        $this->db_connection = new DatabaseConnection();
        $this->pdo = $this->db_connection->getPDO();

        $this->createPayment();
        $sql_select = "SELECT * FROM payments ORDER BY payment_id DESC";
        $stmt = $this->pdo->query($sql_select);
        $last_payment = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->payment_id = $last_payment['payment_id'];
    }

    public function createPayment()
    {
        $sql_insert = "INSERT INTO payments (order_id, payment_date, payment_amount) VALUES (:order_id, :payment_date, :payment_amount)";
        $stmt = $this->pdo->prepare($sql_insert);
        $stmt->execute(array(
                             ':order_id'=>$this->order_id,
                             ':payment_date'=>$this->payment_date,
                             ':payment_amount'=>$this->payment_amount
                            ));
    }

    public function getPaymentId()
    {
        return $this->payment_id;
    }
}
?>