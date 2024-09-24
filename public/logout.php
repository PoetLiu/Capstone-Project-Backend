<?php
require_once __DIR__ . '/../rest/Response.php';
session_start();
session_destroy();

Response::echo(0, null, null);
exit;
?>
