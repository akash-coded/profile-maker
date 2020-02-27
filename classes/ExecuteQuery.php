<?php

/* Debugging */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

spl_autoload_register(function ($class_name) {
	require_once $class_name . '.php';
});

class ExecuteQuery
{
	protected $connectInstance;
	public $connection;

	public function __construct()
	{
		$this->connectInstance = ConnectDB::getInstance();
		$this->connection = $this->connectInstance->getConnection();
	}

	public function begin_transaction()
	{
		$this->connection->beginTransaction();
	}

	public function __call($operation, $params)
	{
		if ($operation === 'select') {
			$query = new SelectQuery($this->connection);
			$result = $query->select($params[0], $params[1]);
		} elseif ($operation === 'insert') {
			$query = new InsertQuery($this->connection);
			$result = $query->insert($params[0], $params[1]);
		} elseif ($operation === 'update') {
			$query = new UpdateQuery($this->connection);
			$result = $query->update($params[0], $params[1], $params[2]);
		} elseif ($operation === 'delete') {
			$query = new DeleteQuery($this->connection);
			$result = $query->delete($params[0], $params[1]);
		} elseif ($operation === 'runPreparedStatement') {
			$result = $this->connection->prepare($params[0]);
			$result->execute($params[1] ?? null);
		}
		return $result;
	}

	public function end_transaction()
	{
		try {
			$this->connection->commit();
		} catch (Exception $e) {
			$this->connection->rollback();
			throw $e;
		}
	}

	public function __destruct()
	{
		$this->connectInstance->closeConnection();
		unset($this->connection);
		unset($this->connectInstance);
	}
}