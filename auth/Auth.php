<?php
require_once __DIR__ . '/../rest/Response.php';
class Auth {
    public static function requireAuthenticated() {
        $userId = $_SESSION['user_id'];
        if ($userId == '') {
            Response::echo(2, "unauthenticated request, please login first", null);
            exit();
        }
    }
}
?>