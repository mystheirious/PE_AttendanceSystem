<?php
require_once 'database.php';

class User extends Database {
    protected $table = "users";

    public function register($full_name, $email, $password, $role) {
        $data = [
            "full_name" => $full_name,
            "email" => $email,
            "password" => password_hash($password, PASSWORD_DEFAULT),
            "role" => $role,
            "date_added" => date("Y-m-d H:i:s")
        ];
        return $this->insert($this->table, $data);
    }

    public function login($email, $password) {
        $user = $this->selectOne($this->table, "email = :email", ["email"=>$email]);
        if($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }
}
?>