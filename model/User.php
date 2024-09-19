<?php
class User {
    private $id;
    private $username;
    private $email;
    private $password_hash;

    public function __construct($id, $username, $email, $password_hash) {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->password_hash = $password_hash;
    }

    function getId() {
        return $this->id;
    }

    function getUsername() {
        return $this->username;
    }

    function getEmail() {
        return $this->email;
    }

    function getPasswordHash() {
        return $this->password_hash;
    }

}
