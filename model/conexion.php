<?php

class MySQLConnection {
    private $host;
    private $port;
    private $username;
    private $password;
    private $db;
    private $pdo;
    private static $instance;
    
    private function __construct() {
        $this->host = 'localhost' ; 
        $this->port = '3306';
        $this->username= 'root';
        $this->password='';
        $this->db='loretosistemas_EvaDiag';
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function connect() {
        if ($this->pdo === null) {
            $dsn = "mysql:host={$this->host};port={$this->port};dbname={$this->db};charset=utf8mb4";
            $this->pdo = new PDO($dsn, $this->username, $this->password, array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ));
        }
        return $this->pdo;
    }
    
    public function execute($sql, $params = array()) {
        $pdo = $this->connect();
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }
    
    public function query($sql, $params = array()) 
    {
        if (empty($params)) {
            $stmt = $this->execute($sql);
        } else {
            $stmt = $this->execute($sql, $params);
        }

        return $stmt;
    }
    
    public function __clone() {
        trigger_error('Clonación no permitida.', E_USER_ERROR);
    }
    
    public function close() {
        $this->pdo = null;
    }
    
    public function __destruct() {
        $this->close();
    }
}
