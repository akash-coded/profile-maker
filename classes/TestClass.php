<?php

require_once 'BaseModel.php';

$obj = new UserModel();

// $tblName = "users";

$selectConditions = array(
	"select_expr" => ["username", "password", "name"],

	"where" => array(
		"predicates" => ["username" => "akash"],
		"comparators" => ["="],
	),

	"limit" => ["row_count" => 1],

	"return_type" => "single-obj",

);

$selectConditionsWithJoin = [
	"select_expr" => [
		"users.id",
		"skills.skillName",
	],

	"join" => [
		"join_type" => ["INNER JOIN", "LEFT OUTER JOIN"],
		"joined_table" => ["user_skills", "skills"],
		"search_condition" => "users.id = user_skills.userId AND user_skills.skillId = skills.id",
		"join_specification" => ["ON users.id = user_skills.userId", "ON user_skills.skillId = skills.id"],
	],

	"where" => array(
		"predicates" => ["skills.skillName" => "JavaScript"],
		"comparators" => ["="],
	),

	"order_by" => [
		"column_name" => "username",
		"direction" => 'DESC',
	],

	"limit" => [
		"row_count" => 1,
	],

	"return_type" => "grouped",
];

$data = [
	"username" => "deepti",
	"password" => "780952@Drb",
];

$assignmentList = [
	"name" => "Deeptiranjan Baliarsingh",
	"email" => "drb@gmail.com",
	"mobile" => 9976557844,
	"age" => 23,
	"gender" => "Genderqueer",
	"state" => "Odisha",
];

$updateConditions = [
	"where" => array(
		"predicates" => ["id" => 1, "username" => "deepti"],
		"comparators" => [">", "="],
		"conjunctions" => ["AND"],
	),
];

$deleteConditions = [
	"where" => array(
		"predicates" => ["profilePic" => null],
		"comparators" => ["<=>"],
	),

	"order_by" => [
		"column_name" => "username",
		"direction" => 'DESC',
	],

	"limit" => [
		"row_count" => 1,
	],

];

$obj->begin_transaction();

// $result = $obj->select($tblName, $selectConditions);
// $result = $obj->select($tblName, $selectConditionsWithJoin);
// $result = $obj->update($tblName, $assignmentList, $updateConditions);
// $result = $obj->delete($tblName, $deleteConditions);

$result = $obj->getUserDetails("deepti", ["username", "password", "name"]);

/* Debugging */
print_r($result);

$obj->end_transaction();

echo dirname(__FILE__), "</br>";
$class_name = "TestQuery";
echo $_SERVER['DOCUMENT_ROOT'] . "/classes/{$class_name}" . ".php";