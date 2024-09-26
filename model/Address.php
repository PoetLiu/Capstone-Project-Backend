<?php
class Address implements JsonSerializable{
    private $id;
    private $user_id;
    private $firstname;
    private $lastname;
    private $phone;
    private $address;
    private $city;
    private $province_id;
    private $postcode;

    public function __construct($id, $user_id, $firstname, $lastanme, $phone, $address, $city, $province_id, $postcode) {
        $this->id = $id;
        $this->user_id = $user_id;
        $this->firstname = $firstname;
        $this->lastname = $lastanme;
        $this->phone = $phone;
        $this->address = $address;
        $this->city = $city;
        $this->province_id = $province_id;
        $this->postcode = $postcode;
    }

    function getId() {
        return $this->id;
    }

    function getUserId() {
        return $this->user_id;
    }

    function getFirstname() {
        return $this->firstname;
    }

    function getLastname() {
        return $this->lastname;
    }


    function getPhone() {
        return $this->phone;
    }

    function getAddress() {
        return $this->address;
    }

    function getCity() {
        return $this->city;
    }

    function getProvinceId() {
        return $this->province_id;
    }

    function jsonSerialize():mixed{
        return get_object_vars($this);
    }
}

?>