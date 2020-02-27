<?php

/* Debugging */
//require_once 'Config.php';

class ConnectDB {
	private static $instance = null;
	private $conn;

	private $host;
	private $username;
	private $password;
	private $dbName;

	private function __construct($user_config = null) {
		try {
			if ($user_config) {
				$config = $user_config;
			} else {
				$config = new Config();
			}

			$this->host = $config->host;
			$this->dbName = $config->dbName;
			$this->username = $config->username;
			$this->password = $config->password;

			$this->conn = new PDO(
				"mysql:host={$this->host};dbname={$this->dbName}",
				$this->username,
				$this->password,
				array(
					PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci",
					PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
					PDO::ATTR_EMULATE_PREPARES => false,
				)
			);
			//this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			/* Debugging */
			// echo "DB Connected with settings: {$this->host}, {$this->dbName}, {$this->username}, {$this->password}</br>";
		} catch (PDOException $e) {
			throw new Exception("Error while connecting to Database!", $e->getCode());
			//die("Failed to connect with MySQL: " . $e->getMessage());
		}
	}

	public static function getInstance() {
		if (!self::$instance) {
			self::$instance = new ConnectDb();
		}

		return self::$instance;
	}

	public function getConnection() {
		/* Debugging */
		// echo "Throwing connection for use</br>";

		return $this->conn;
	}

	public function closeConnection() {
		if ($this->conn) {
			unset($this->conn);
			self::$instance = null;
			return true;
		}

		return false;
	}
}

/* Debugging */
/*
// Establising DB connection
$connectionInstance = ConnectDB::getInstance();
$connection = $connectionInstance->getConnection();

if ($connection) {
echo "Connection established</br>";
} else {
echo "Connection failed</br>";
}

$attemptAnotherConnection = ConnectDB::getInstance();
if ($connectionInstance == $attemptAnotherConnection) {
echo "Connection exists</br>";
} else {
echo "New connection</br>";
}

// Closing the DB connection
echo "{$connectionInstance->closeConnection()}</br>";
unset($connection);
unset($connectionInstance);

if (!$connection) {
echo "Connection closed</br>";
} else {
echo "Connection closing failed</br>";
}

// Attempting connection after previous connection has been disconnected
$attemptAnotherConnection = ConnectDB::getInstance();
if ($connectionInstance == $attemptAnotherConnection) {
echo "Connection exists</br>";
} else {
echo "New connection</br>";
}
 */