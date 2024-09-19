<?php
class Response {
    public $status;
    public $msg;
    public $data;

    public function __construct($status, $msg, $data) {
        $this->status = $status;
        $this->msg = $msg;
        $this->data = $data;
    }

    public function render() {
        if ($this->status == 0) {
            http_response_code(200);
        } else {
            http_response_code(400);
        }
        header('Content-Type: application/json; charset=utf-8');

        // disables CORS protection
        header('Access-Control-Allow-Origin: *');
        echo json_encode($this);
    }

}