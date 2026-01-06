<?php
require_once 'Database.php';

class Transaction {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function pay($user_id, $product_id, $qty) {
        $stmt = $this->conn->prepare(
            "SELECT price FROM products WHERE id=:id"
        );
        $stmt->execute(['id'=>$product_id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        $total = $product['price'] * $qty;

        $stmt = $this->conn->prepare(
            "INSERT INTO transactions(user_id, product_id, quantity, total)
             VALUES(:u, :p, :q, :t)"
        );
        $stmt->execute([
            'u'=>$user_id,
            'p'=>$product_id,
            'q'=>$qty,
            't'=>$total
        ]);

        return ["message"=>"Payment success", "total"=>$total];
    }

    public function getAll() {
        $stmt = $this->conn->query(
            "SELECT t.id, u.username, p.name AS product, t.quantity, t.total, t.created_at
             FROM transactions t
             JOIN users u ON t.user_id = u.id
             JOIN products p ON t.product_id = p.id
             ORDER BY t.created_at DESC"
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
