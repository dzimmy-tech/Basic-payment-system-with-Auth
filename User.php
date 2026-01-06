<?php
require_once 'Database.php';

class User {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function register($username, $password) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare(
            "INSERT INTO users(username, password) VALUES(:u, :p)"
        );
        $stmt->execute(['u'=>$username, 'p'=>$hash]);
        return ["message"=>"User registered"];
    }

    public function login($username, $password) {
        $stmt = $this->conn->prepare(
            "SELECT * FROM users WHERE username=:u"
        );
        $stmt->execute(['u'=>$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if($user && password_verify($password, $user['password'])) {
            return ["message"=>"Login success", "user_id"=>$user['id']];
        }
        return ["message"=>"Login failed"];
    }
}
