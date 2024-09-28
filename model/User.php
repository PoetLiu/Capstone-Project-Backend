<?php
class User implements JsonSerializable{
    private $id;
    private $username;
    private $email;
    private $phone;
    private $photoUrl;
    private $password_hash;
    private $billing_address;
    private $shipping_address;

    public function __construct($id, $username, $email,  $phone, $photoUrl, $password_hash, $billing_address = null, $shipping_address = null) {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->phone = $phone;
        $this->photoUrl = $photoUrl;
        $this->password_hash = $password_hash;
        $this->billing_address = $billing_address;
        $this->shipping_address = $shipping_address;
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

    function jsonSerialize():mixed{
        return get_object_vars($this);
    }
}
