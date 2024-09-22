<?php
require_once __DIR__ . '/..//model/User.php';
class UserDAO {
    private $conn;
    public function __construct($db) {
        $this->conn = $db;
    }

    // Register a new user
    public function register($newUser) {
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

    public function login($email, $password) {
        $query = "SELECT id, username, email, password_hash FROM users WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $user = new User($row['id'], $row['username'], $row['email'], $row['password_hash']);

            if (password_verify($password, $user->getPasswordHash())) {
                return $user;
            }
        }

        return false;
    }

    public function getOne($email) {
        $query = "SELECT id, username, email, password_hash FROM users WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return new User($row['id'], $row['username'], $row['email'], $row['password_hash']);
        }

        return false;
    }

    public function resetPWD($email, $newPWD) {
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
}
