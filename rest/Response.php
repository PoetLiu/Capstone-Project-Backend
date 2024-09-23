<?php
class Response {
    public function __construct() {
    }

    public static function echo($status, $msg, $data) {
        if ($status == 0) {
            http_response_code(200);
        } else {
            http_response_code(400);
        }
        header('Content-Type: application/json; charset=utf-8');

        // disables CORS protection
        header('Access-Control-Allow-Origin: http://localhost:3000');
        echo json_encode([
            "status" => $status,
            "msg" => $msg,
            "data" => $data
        ]);
    }

}
