<?php
require_once __DIR__ . '/../model/User.php';
class UserDAO
{
    private $conn;
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Register a new user
    public function register($newUser)
    {
        // Check if the email already exists
        $query = "SELECT id FROM users WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $newUser->getEmail());
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return "email_exists";
        }

        // Insert new user into the database
        $query = "INSERT INTO users SET username = :username, email = :email, password_hash = :password_hash";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':username', $newUser->getUsername());
        $stmt->bindParam(':email', $newUser->getEmail());
        $stmt->bindParam(':password_hash', $newUser->getPasswordHash());

        try {
            return $stmt->execute();
        } catch (PDOException $e) {
            // Log the exception message or handle it
            error_log("Error during registration: " . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function login($email, $password)
    {
        $query = "SELECT id, username, email, password_hash FROM users WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $user = new User($row['id'], $row['username'], $row['email'], $row['phone'],null, $row['password_hash']);

            if (password_verify($password, $user->getPasswordHash())) {
                return $user;
            }
        }

        return false;
    }

    public function getOne($email)
    {
        $query = "SELECT id, username, email, password_hash FROM users WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return new User($row['id'], $row['username'], $row['email'], $row['phone'],null, $row['password_hash']);
        }

        return false;
    }

    public function getOneById($id)
    {
        $query = "SELECT users.id, users.username, users.email, users.phone, users.password_hash, users.photoUrl, 
            users.billing_address_id,
            address.id as address_id,
            address.firstname,
            address.lastname,
            address.phone,
            address.address,
            address.city,
            address.province_id,
            address.postcode 
            FROM users 
            LEFT JOIN address ON address.id = users.billing_address_id
            WHERE users.id = :id LIMIT 1"
        ;
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $address = null;
            if ($row["billing_address_id"]) {
                $address = new Address($row['address_id'], $row['id'], $row['firstname'], $row['lastname'], 
                $row['phone'], $row['address'], $row['city'], $row['province_id'], $row['postcode']);
            }
            return new User($row['id'], $row['username'], $row['email'], $row['phone'], $row['photoUrl'], $row['password_hash'], $address);
        }

        return false;
    }

    public function resetPWD($email, $newPWD)
    {
        $query = "SELECT id FROM users WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() == 0) {
            return "email_not_exists";
        }

        $query = "UPDATE users SET password_hash = :password_hash WHERE email = :email";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password_hash', $newPWD);

        try {
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error during reseting password: " . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function update($id, $name, $email, $phone)
    {
        $query = "UPDATE users SET username = :name, email = :email, phone = :phone WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);

        try {
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error during updating: " . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function updateAddr($id, $addressId, $firstname, $lastname, $phone, $address, $city, $province_id, $postcode)
    {
        if ($addressId) {
            $query = "UPDATE address SET firstname = :firstname, lastname=:lastname, phone=:phone, address= :address, city= :city, 
                province_id = :province_id, postcode = :postcode 
                WHERE id = :address_id";
            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(':address_id', $addressId);
            $stmt->bindParam(':firstname', $firstname);
            $stmt->bindParam(':lastname', $lastname);
            $stmt->bindParam(':phone', $phone);
            $stmt->bindParam(':address', $address);
            $stmt->bindParam(':city', $city);
            $stmt->bindParam(':province_id', $province_id);
            $stmt->bindParam(':postcode', $postcode);
        } else {
            $query = "INSERT INTO address(user_id, firstname, lastname, phone, address, city, province_id, postcode) 
                VALUES(:user_id, :firstname, :lastname, :phone, :address, :city, :province_id, :postcode)
            ";
            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(':user_id', $id);
            $stmt->bindParam(':firstname', $firstname);
            $stmt->bindParam(':lastname', $lastname);
            $stmt->bindParam(':phone', $phone);
            $stmt->bindParam(':address', $address);
            $stmt->bindParam(':city', $city);
            $stmt->bindParam(':province_id', $province_id);
            $stmt->bindParam(':postcode', $postcode);

            try {
                $stmt->execute();
            } catch (PDOException $e) {
                error_log("Error during inserting address: " . $e->getMessage());
                return $e->getMessage();
            }

            $last_id = $this->conn->lastInsertId();
            $query = "UPDATE users SET billing_address_id = :billing_address_id WHERE id = :id";
            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':billing_address_id', $last_id);
        }

        try {
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error during updating: " . $e->getMessage());
            return $e->getMessage();
        }
    }


    public function updatePWD($id, $oldPwd, $newPwdHash)
    {
        $user = $this->getOneById($id);
        if (!password_verify($oldPwd, $user->getPasswordHash())) {
            return "old password is incorrect.";
        }

        $query = "UPDATE users SET password_hash = :password_hash WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':password_hash', $newPwdHash);

        try {
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error during updating password: " . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function updatePhoto($id, $name)
    {
        $query = "UPDATE users SET photoUrl= :name WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $name);

        try {
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error during updating photo: " . $e->getMessage());
            return $e->getMessage();
        }
    }
}
