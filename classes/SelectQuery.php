<?php
class SelectQuery extends Query
{
	/*
		***********************************************
		Input format for select
		***********************************************
		$conditions = array(
				"select_expr" => ["username", "password"],
				"join" => array(
								"join_type" => ["INNER JOIN", "LEFT OUTER JOIN"],
								"joined_table" => ["user_skills", "skills"],
								"search_condition" => "users.id = user_skills.userId AND user_skills.skillId = skills.id",
								"join_specification" => ["ON users.id = user_skills.userId", "ON user_skills.skillId = skills.id"],
								),
				"where" => array(
								"predicates" => ["salary" => "5000", "name" => "akash"],
								"comparators" => [">", "="],
								"conjunctions" => ["AND"],
								),
				"order_by" => array(
									"column_names" => ["name", "email"],
									"directions" => ["DESC", "ASC"],
									),
				"limit" => ["offset" => 1, "row_count" => 1],
				"return_type" => "single",
		);
	*/
	public function select(string $tblName, array $conditions = [])
	{
		$values = [];
		$sql = 'SELECT ';
		$sql .= array_key_exists("select_expr", $conditions) ? implode(', ', $conditions['select_expr']) : 'COUNT(*)';
		$sql .= ' FROM ' . $tblName;

		/* If the stmt has JOIN expression */
		if (array_key_exists("join", $conditions)) {
			$sql .= $this->join($conditions['join']);
		}

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

		$stmt = $this->dbh->prepare($sql);
		$stmt->execute($values);

		if (array_key_exists("return_type", $conditions) && $conditions['return_type'] != 'all') {
			switch ($conditions['return_type']) {
				case 'count':
					$data = $stmt->rowCount();
					break;
				case 'single':
					$data = $stmt->fetch(PDO::FETCH_BOTH);
					break;
				case 'single-obj':
					$data = $stmt->fetch(PDO::FETCH_OBJ);
					break;
				case 'single-lazy':
					$data = $stmt->fetch(PDO::FETCH_LAZY);
					break;
				case 'column':
					$data = $stmt->fetchAll(PDO::FETCH_COLUMN);
					break;
				case 'key-value-pair':
					$data = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
					break;
				case 'all-by-unique-key':
					$data = $stmt->fetchAll(PDO::FETCH_UNIQUE);
					break;
				case 'grouped':
					$data = $stmt->fetchAll(PDO::FETCH_GROUP);
					break;
				case 'column-after-grouping':
					$data = $stmt->fetchAll(PDO::FETCH_GROUP | PDO::FETCH_COLUMN);
					break;
				default:
					$data = '';
			}
		} else {
			if ($stmt->rowCount() > 0) {
				$data = $stmt->fetchAll(PDO::FETCH_BOTH);
			}
		}

		return !empty($data) ? $data : null;
	}
}