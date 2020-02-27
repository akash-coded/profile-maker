<?php
class DeleteQuery extends Query
{
	public function delete($tblName, $conditions)
	{
		$sql = "DELETE FROM " . $tblName;
		$values = [];

		if (!empty($conditions) && is_array($conditions)) {
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
		}

		$stmt = $this->dbh->prepare($sql);

		$deleted = $stmt->execute($values);

		return $deleted && $stmt->rowCount();
	}
}