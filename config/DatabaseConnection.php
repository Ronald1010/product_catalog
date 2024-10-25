<?php

namespace Config;

use PDO;
use PDOException;
use Dotenv\Dotenv;

class DatabaseConnection
{
    private $connection;

    public function __construct()
    {
        // Load environment variables from .env
        $dotenv = Dotenv::createImmutable(__DIR__ . '/..'); // Assuming .env is one level up from config/
        $dotenv->load();

        $host = $_ENV['DB_HOST'];
        $port = $_ENV['DB_PORT'];
        $db = $_ENV['DB_DATABASE'];
        $user = $_ENV['DB_USERNAME'];
        $password = $_ENV['DB_PASSWORD'];

        $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";

        try {
            // Establish a PDO connection
            $this->connection = new PDO($dsn, $user, $password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    // Get the database connection instance
    public function getConnection()
    {
        return $this->connection;
    }

    // Function for executing queries with parameter binding
    public function query($sql, $params = [])
    {
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }
}
