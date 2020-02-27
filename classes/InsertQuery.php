<?php
class InsertQuery extends Query
{
	/*
		***********************************************
		Input format for insert
		***********************************************
		$data = array(
				"join" => array(),
				"where" => array(
								"predicates" => ["salary" => "5000", "name" => "akash"],
								"comparators" => [">", "="],
								"conjunctions" => ["AND"],
								),
				"order_by" => array(
									"column_names" => ["name", "email"],
									"directions" => ["DESC", "ASC"],
									),
		);
	*/
	public function insert(string $tblName, array $data)
	{
		if (!empty($data) && is_array($data)) {
			$columnString = implode(',', array_keys($data));
			$valueString = ":" . implode(',:', array_keys($data));

			$sql = "INSERT INTO " . $tblName . " (" . $columnString . ") VALUES (" . $valueString . ")";

			$stmt = $this->dbh->prepare($sql);

			$inserted = $stmt->execute($data);

			return $inserted && $this->dbh->lastInsertId();
		} else {
			return false;
		}
	}
}