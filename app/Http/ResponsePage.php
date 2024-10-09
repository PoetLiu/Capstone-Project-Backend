<?php

namespace App\Http;

class ResponsePage extends Response {
    public function __construct($status, $msg, $total, $pageSize, $pageNum, $list) {
        parent::__construct($status, $msg, 
            [ "total" => $total, "page_size" => $pageSize,  "page_num" => $pageNum, "list" => $list]);
    }
}
