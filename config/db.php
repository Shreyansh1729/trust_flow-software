<?php
/**
 * Database Configuration and Connection
 * 
 * Uses PDO for secure database interactions.
 * Defines constants for connection parameters.
 */

// Define DB Credentials (Loaded from .env)
require_once __DIR__ . '/../includes/Env.php';
Env::load(__DIR__ . '/../.env');

defined('DB_HOST') || define('DB_HOST', getenv('DB_HOST') ?: '127.0.0.1');
defined('DB_NAME') || define('DB_NAME', getenv('DB_NAME') ?: 'trust_flow_db');
defined('DB_USER') || define('DB_USER', getenv('DB_USER') ?: 'root');
defined('DB_PASS') || define('DB_PASS', getenv('DB_PASS') ?: '');
defined('DB_CHARSET') || define('DB_CHARSET', 'utf8mb4');

class Database {
    private $host = DB_HOST;
    private $db_name = DB_NAME;
    private $username = DB_USER;
    private $password = DB_PASS;
    private $charset = DB_CHARSET;
    public $pdo;
    public $error;

    public function __construct() {
        $this->connect();
    }

    private function connect() {
        $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=" . $this->charset;
        
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $this->pdo = new PDO($dsn, $this->username, $this->password, $options);
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            // log_error($this->error); // Optional: Log to file
            // In production, do not echo the error directly to user
            die("Database Connection Failed. Please check your configuration.");
        }
    }

    /**
     * Get the PDO instance
     */
    public function getConnection() {
        return $this->pdo;
    }
}

// Global helper to get DB connection
// Usage: $conn = getDB();
function getDB() {
    static $db_instance = null;
    if ($db_instance === null) {
        $db = new Database();
        $db_instance = $db->getConnection();
    }
    return $db_instance;
}
