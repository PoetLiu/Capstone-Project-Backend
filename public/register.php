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
    $name = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    if ($name == '' || $email == '' || $password == '') {
        Response::echo(1, "Name, email or password is missing, please check your parameters.", null);
        exit();
    }

    $password_hash = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $user = new User(null, null, $name, $email, $password_hash);

    $result = $userDAO->register($user);

    $status = 1;
    if ($result === true) {
        $msg = "OK";
        $status = 0;
    } elseif ($result === "email_exists") {
        $msg = "The email address is already registered. Please use a different email.";
    } else {
        $msg = "There was an issue with your registration. Please try again. $result";
    }
    Response::echo($status, $msg, null);
}
?>