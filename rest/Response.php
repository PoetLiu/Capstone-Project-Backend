<?php
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING | E_DEPRECATED));
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
        header('Access-Control-Allow-Credentials: true');
        echo json_encode(new ResponseBody($status, $msg, $data));
    }
}

class ResponseBody {
    public $status;
    public $msg;
    public $data;
    public function __construct($status, $msg, $data) {
        $this->status = $status;
        $this->msg = $msg;
        $this->data = $data;
    }
}
