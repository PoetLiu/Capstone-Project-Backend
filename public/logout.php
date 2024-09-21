<?php
require_once '../rest/Response.php';
session_start();
session_destroy();

$resp = new Response(0, null, null);
$resp->render();
exit;
?>
