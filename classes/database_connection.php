<?php
    class DatabaseConnection
    {
        private $db_username = 'root';
        private $db_password = '';
        private $db_name = 'laundry_system';
        private $host = 'localhost';
        private $port = '3306';
        
        private $dsn;
        private $pdo;

        public function __construct()
        {
            $this->dsn = 'mysql:host='.$this->host.';port='.$this->port.';dbname='.$this->db_name;
            $this->pdo = new PDO($this->dsn, $this->db_username, $this->db_password);
        }

        public function getPDO()
        {
            return $this->pdo;
        }
    }
?>