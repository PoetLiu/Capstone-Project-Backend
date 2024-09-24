<?php
session_start();
require_once __DIR__ . '/../database/Database.php';
require_once __DIR__ . '/../model/User.php';
require_once __DIR__ . '/../dao/UserDAO.php';
require_once __DIR__ . '/../rest/Response.php';

error_reporting(E_ALL ^ (E_NOTICE | E_WARNING | E_DEPRECATED));

$database = new Database();
$db = $database->getConnection();

$userDAO = new UserDAO($db);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $status = 0;
    $msg = "OK";
    $result = $userDAO->login($email, $password);
    if ($result) {
        $_SESSION['user_id'] = $result->getId();
    } else {
        $status = 1;
        $msg = "Invalid email or password";
    }

    Response::echo($status, $msg, $result);
}
?>