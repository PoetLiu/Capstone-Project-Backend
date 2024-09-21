<?php
require_once '../rest/Response.php';
session_start();
session_destroy();

Response::echo(0, null, null);
exit;
?>
