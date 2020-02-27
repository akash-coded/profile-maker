<?php

/* Debugging */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

spl_autoload_register(function ($class_name) {
    require_once $class_name . '.php';
});

class UserModel extends BaseModel
{
    protected $tblName = 'users';

    public function getUserDetails(string $user, array $columns = [])
    {
        $conditions = array(
            "where" => array(
                "predicates" => ["username" => $user],
                "comparators" => ["="],
            ),

            "limit" => ["row_count" => 1],

            "return_type" => "single-obj",
        );

        $conditions["select_expr"] = empty($columns) ? array("*") : $columns;

        return $this->select($this->tblName, $conditions);
    }

    public function getAllUsers()
    {
    }

    public function setUserDetails($user, $details)
    {
    }

    public function updateUserDetails($user, $details)
    {
    }

    public function createUser(string $username, string $password)
    {
    }
}