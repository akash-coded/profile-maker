<?php

class Query
{
	protected $dbh;

	public function __construct($conn)
	{
		$this->dbh = $conn;
	}

	protected function join(array $joinCondition): string
	{
		$joinExpression = '';

		$joinTypeSpecified = array_key_exists("join_type", $joinCondition);

		$noOfJoins = count($joinCondition["joined_table"]);

		if ($joinTypeSpecified) {
			for ($i = 0; $i < $noOfJoins; $i++) {
				$joinExpression .= ' ' . $joinCondition["join_type"][$i]
					. ' ' . $joinCondition["joined_table"][$i]
					. ' ' . $joinCondition["join_specification"][$i];
			}
		} else {
			$joinExpression .= ' , ' . implode(', ', $joinCondition["joined_table"]);
			$joinExpression .= array_key_exists("search_condition", $joinCondition)
				? ' WHERE ' . $joinCondition['search_condition'] : '';
		}

		return $joinExpression;
	}

	protected function where(array $whereCondition, array &$values): string
	{
		$whereClause = ' WHERE ';
		$i = 0;
		$j = 0;

		$hasComparators = array_key_exists("comparators", $whereCondition);
		$hasConjunctions = array_key_exists("conjunctions", $whereCondition);

		$comparatorCount = $hasComparators ? count($whereCondition["comparators"]) : 0;
		$conjunctionCount = $hasConjunctions ? count($whereCondition["conjunctions"]) : 0;

		foreach ($whereCondition["predicates"] as $predicate_key => &$predicate_value) {
			$whereClause .= $predicate_key;

			$whereClause .= ($hasComparators && ($i < $comparatorCount)) ?
				' ' . $whereCondition["comparators"][$i++] . ' ' : ' = ';

			if (is_array($predicate_value)) {
				$whereClause .= "('" . implode("', '", $predicate_value) . "')";
				unset($whereCondition['predicates'][$predicate_key]);
			} else {
				// In case the column name is referenced by table name
				if (strpos($predicate_key, '.') !== false) {
					unset($whereCondition['predicates'][$predicate_key]);
					$predicate_key = str_replace('.', '_', $predicate_key);
					$values[$predicate_key] = $predicate_value;
				}
				$whereClause .= ':' . $predicate_key;
			}

			$whereClause .= !($hasConjunctions && ($j < $conjunctionCount)) ?
				'' : ' ' . $whereCondition["conjunctions"][$j++] . ' ';
		}

		$values = array_merge($values, $whereCondition['predicates']);

		return $whereClause;
	}

	protected function order_by(array $orderByCondition): string
	{
		$orderByClause = ' ORDER BY ';

		if (array_key_exists("column_names", $orderByCondition)) {
			$hasDirections = array_key_exists("directions", $orderByCondition);
			$totalColumns = count($orderByCondition['column_names']);

			for ($i = 0; $i < $totalColumns; $i++) {
				$orderByClause .= ($i > 0) ? ' , ' : '';
				$orderByClause .= $orderByCondition['column_names'][$i];
				$direction = ($hasDirections && ($orderByCondition['directions'][$i]) == 'DESC') ?
					' DESC' : ' ASC';
				$orderByClause .= $direction;
			}
		} else {
			$orderByClause .= $orderByCondition['column_name'];

			$direction = (array_key_exists("direction", $orderByCondition)
				&& ($orderByCondition['direction'] == 'DESC')) ? ' DESC' : ' ASC';

			$orderByClause .= $direction;
		}

		return $orderByClause;
	}

	protected function limit(array $limitCondition, array &$values): string
	{
		$limitClause = " LIMIT ";

		$limitClause .= array_key_exists("offset", $limitCondition) ? ":offset , " : '';
		$limitClause .= ":row_count";

		$values = array_merge($values, $limitCondition);

		return $limitClause;
	}
}