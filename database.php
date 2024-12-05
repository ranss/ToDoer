<?php

class Database {
    private static $instance = null; // Singleton instance
    private $connection;             // Database connection
    private $host;
    private $username;
    private $password;
    private $dbname;

    // Private constructor to prevent direct object creation
    private function __construct($host, $username, $password, $dbname) {
        $this->host = $host;
        $this->username = $username;
        $this->password = $password;
        $this->dbname = $dbname;

        // Establish the database connection
        $this->connection = new mysqli($host, $username, $password, $dbname);

        // Check for connection errors
        if ($this->connection->connect_error) {
            die("Connection failed: " . $this->connection->connect_error);
        }
    }

    // Get the singleton instance
    public static function getInstance($host, $username, $password, $dbname) {
        if (self::$instance === null) {
            self::$instance = new Database($host, $username, $password, $dbname);
        }
        return self::$instance;
    }

    // Get the database connection
    public function getConnection() {
        return $this->connection;
    }

    // Close the database connection
    public function close() {
        if ($this->connection) {
            $this->connection->close();
            self::$instance = null; // Reset singleton
        }
    }
}