<?php
require_once __DIR__ . '/../rest/Response.php';
require_once __DIR__ . '/../auth/Auth.php';

session_start();
Auth::requireAuthenticated();
session_destroy();

Response::echo(0, null, null);
exit;
?>
