<?php
session_start();
require_once __DIR__ . '/../database/Database.php';
require_once __DIR__ . '/../model/User.php';
require_once __DIR__ . '/../model/Address.php';
require_once __DIR__ . '/../dao/UserDAO.php';
require_once __DIR__ . '/../rest/Response.php';
require_once __DIR__ . '/../auth/Auth.php';

error_reporting(E_ALL ^ (E_NOTICE | E_WARNING | E_DEPRECATED));
Auth::requireAuthenticated();

$database = new Database();
$db = $database->getConnection();

$userDAO = new UserDAO($db);
$action = trim($_POST['action']);
$userId = $_SESSION['user_id'];

try {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $data = null;
        $result = null;
        if ($action == "UPDATE_PHOTO") {
            $name = trim($_POST['photo_name']);
            if ($name == '') {
                throw new RuntimeException('photo_name is missing, please check your parameters.');
            }
            $path = $dir = __DIR__ . "/images/$name";
            if (!file_exists($path)) {
                throw new RuntimeException("file:$name doesn't exist, please upload it first.");
            }
            $result = $userDAO->updatePhoto($userId, $name);
        } else if ($action == "UPDATE_BASIC_INFO") {
            $name = trim($_POST['username']);
            $email = trim($_POST['email']);
            $phone = trim($_POST['phone']);
            if ($name == '' || $email == '' || $phone == '') {
                throw new RuntimeException('Name, email or phone is missing, please check your parameters.');
            }
            $result = $userDAO->update($userId, $name, $email, $phone);
        } else if ($action == "UPDATE_BILLING_ADDRESS") {
            $id = trim($_POST['id']);
            $firstname = trim($_POST['firstname']);
            $lastname = trim($_POST['lastname']);
            $phone = trim($_POST['phone']);
            $address = trim($_POST['address']);
            $city = trim($_POST['city']);
            $provinceId = trim($_POST['province_id']);
            $postcode = trim($_POST['postcode']);
            if ($firstname == '' || $lastname == '' ||  $phone == '' || $address == '' || $city == '' || $provinceId == '' || $postcode == '') {
                throw new RuntimeException('One or more parameters are missing, please check your parameters.');
            }
            $result = $userDAO->updateAddr(
                $userId,
                $id,
                $firstname,
                $lastname,
                $phone,
                $address,
                $city,
                $provinceId,
                $postcode
            );
        } else if ($action == "UPDATE_PASSWORD") {
            $oldPwd = trim($_POST['old_password']);
            $newPwd = trim($_POST['new_password']);
            if ($oldPwd == '' || $newPwd == '') {
                throw new RuntimeException('old_password or new_password is missing, please check your parameters.');
            }
            $newPwdHash = password_hash($newPwd, PASSWORD_BCRYPT);
            $result = $userDAO->updatePWD($userId, $oldPwd, $newPwdHash);
        } else {
            throw new RuntimeException('Unknown action.');
        }

        if ($result != true || is_string($result) ) {
            throw new RuntimeException("There was an issue with your updating. Please try again. $result");
        }
    } else if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        $user = $userDAO->getOneById($userId);
        $data = $user;
    } else {
        throw new RuntimeException('Unknown method.');
    }
    Response::echo(0, "OK", $data);
} catch (RuntimeException $e) {
    Response::echo(1, $e->getMessage(), null);
}
?>