<?php

namespace App\Http;

class Response {
    public $status;
    public $msg;
    public $data;
    public function __construct($status, $msg, $data) {
        $this->status = $status;
        $this->msg = $msg;
        $this->data = $data;
    }
}
