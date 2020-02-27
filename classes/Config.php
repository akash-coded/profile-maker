<?php

class Config {
	private $host;
	private $dbName;
	private $username;
	private $password;

	public function __construct($host = "localhost", $dbName = "profile_maker", $username = "akash", $password = "password") {
		$this->host = $host;
		$this->dbName = $dbName;
		$this->username = $username;
		$this->password = $password;

		/* Debugging */
		// echo "Configuration object created</br>";
	}

	public function __get($property) {
		if (property_exists($this, $property)) {
			return $this->$property;
		}
	}

	public function __set($property, $value) {
		if (property_exists($this, $property)) {
			$this->$property = $value;
		}
	}

/* Getters to be used in absence of __get() method */
/*	public function getHost() {
return $this->host;
}

public function getDBName() {
return $this->dbName;
}

public function getUsername() {
return $this->username;
}

public function getPassword() {
return $this->password;
}*/
}
