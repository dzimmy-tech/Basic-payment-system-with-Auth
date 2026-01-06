<?php
header("Content-Type: application/json");

require_once 'User.php';
require_once 'Product.php';
require_once 'Transaction.php';

$action = $_GET['action'] ?? '';
$data = json_decode(file_get_contents("php://input"), true);

switch($action) {

    case 'register':
        $u = new User();
        echo json_encode($u->register($data['username'], $data['password']));
        break;

    case 'login':
        $u = new User();
        echo json_encode($u->login($data['username'], $data['password']));
        break;

    case 'products':
        $p = new Product();
        echo json_encode($p->getAll());
        break;

    case 'pay':
        $t = new Transaction();
        echo json_encode(
            $t->pay($data['user_id'], $data['product_id'], $data['qty'])
        );
        break;

    case 'transactions':
        $t = new Transaction();
        echo json_encode($t->getAll());
        break;

    default:
        echo json_encode(["message"=>"Invalid action"]);
}
