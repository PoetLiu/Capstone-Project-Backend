<?php

session_start();
require_once __DIR__ . '/../database/Database.php';
require_once __DIR__ . '/../mail/Mailer.php';
require_once __DIR__ . '/../dao/UserDAO.php';
require_once __DIR__ . '/../rest/Response.php';

error_reporting(E_ALL ^ (E_NOTICE | E_WARNING | E_DEPRECATED));
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    Response::echo(1, "Unsupported http method.", null);
    exit();
}

$database = new Database();
$db = $database->getConnection();
$userDAO = new UserDAO($db);

$action = trim($_POST['action']);
if ($action == "SEND_EMAIL_LINK") {
    $email = trim($_POST['email']);

    $user = $userDAO->getOne($email);
    if (!$user) {
        Response::echo(1, "Unknown email, please check your input or register a new account.", null);
        exit();
    }

    $_SESSION['token'] = uniqid();
    $mailer = new Mailer();
    $mailer->sendResetPasswordLink($user->getEmail(), $user->getUsername(),
     session_id(), $_SESSION['token']);
} else if ($action == "RESET") {
    $token = trim($_POST['token']);
    $email = trim($_POST['email']);
    $newPWD = trim($_POST['new_password']);

    if ($token == '' || $token != $_SESSION['token']) {
        Response::echo(1, "Invalid link, please try to reset your password again.", null);
        exit();
    } else {
        $_SESSION['token'] = '';
        $hash = password_hash($newPWD, PASSWORD_BCRYPT);
        $result = $userDAO->resetPWD($email, $hash);
        $status = 1;
        if ($result === true) {
            $status = 0;
            session_destroy();
        } elseif ($result === "email_not_exists") {
            $msg = "The email is unknown.";
        } else {
            $msg = "There was an issue with your registration. Please try again. $result";
        }
        Response::echo($status, $msg, null);
        exit();
    }
} else {
    Response::echo(1, "Unknown action, please your parameters.", null);
    exit();
}

Response::echo(0, null, null);
?>