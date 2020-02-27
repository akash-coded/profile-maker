<?php
class UpdateQuery extends Query
{
	/*
		***********************************************
		Input format for update
		***********************************************
		$assignmentList = ["name" => "Deeptiranjan Baliarsingh",
							 "email" => "drb@gmail.com",
							 "mobile" => 9976557844,
							 "age" => 23,
							 "gender" => "Genderqueer",
							 "state" => "Odisha",
							];

		$conditions = array(
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
	public function update(string $tblName, array $assignmentList, array $conditions = [])
	{
		if (!empty($assignmentList) && is_array($assignmentList)) {
			$sql = "UPDATE " . $tblName;
			$values = [];
			$i = 0;

			/* If the stmt has JOIN expression */
			if (!empty($conditions) && array_key_exists("join", $conditions)) {
				$sql .= $this->join($conditions['join']);
			}

			$sql .= " SET ";

			foreach (array_keys($assignmentList) as $col_name) {
				$sql .= ($i > 0) ? ', ' : '';
				$sql .= $col_name . " = :" . $col_name;
				$i++;
			}
			$values = array_merge($values, $assignmentList);

			if (!empty($conditions) && is_array($conditions)) {
				/* If the stmt has WHERE clause */
				if (array_key_exists("where", $conditions)) {
					$sql .= $this->where($conditions['where'], $values);
				}

				/* If the stmt has ORDER BY clause */
				if (array_key_exists("order_by", $conditions)) {
					$sql .= $this->order_by($conditions['order_by']);
				}

				/* If the stmt has LIMIT clause */
				if (array_key_exists("limit", $conditions)) {
					$sql .= $this->limit($conditions['limit'], $values);
				}
			}

			$stmt = $this->dbh->prepare($sql);

			$updated = $stmt->execute($values);

			return $updated && $stmt->rowCount();
		} else {
			return false;
		}
	}
}