<?php

namespace srs\Classes;

require_once "../Config/config.php";
use src\Classes\Database as DatabaseAlias;

class Registration extends DatabaseAlias
{
    private $email;
    private $password;
    private $repit_password;
    private $phone_number;
    private $name;
    private $came_from;
    private $date_birth;

    public function __construct(string $email, string $password, string $repit_password, string $phone_number, string $name, string $came_from, string $date_birth)
    {
        $this->email = $email;
        $this->password = $password;
        $this->repit_password = $repit_password;
        $this->phone_number = $phone_number;
        $this->name = $name;
        $this->came_from = $came_from;
        $this->date_birth = $date_birth;

        global $host, $user, $dbPassword, $db;
        parent::__construct($host, $user, $dbPassword, $db);
    }

    public function registerUser() {
        $validationErrors = $this->validateData();
        if (!empty($validationErrors)) {
            return [
                "status" => false,
                "message" => $validationErrors
            ];
        }

        $hashedPassword = password_hash($this->password, PASSWORD_DEFAULT);
        $query = "INSERT INTO user (email, password, phone_number, name, came_from, date_birth) VALUES (?, ?, ?, ?, ?, ?)";
        $statement = $this->dbConnect->prepare($query);

        if ($statement) {
            $statement->bind_param("ssssss", $this->email, $hashedPassword, $this->phone_number, $this->name, $this->came_from, $this->date_birth);
            if ($statement->execute()) {
                return [
                    "status" => true,
                    "message" => "Success, you are registered"
                ];
            } else {
                return [
                    "status" => false,
                    "message" => $statement->error
                ];
            }
        } else {
            return [
                "status" => false,
                "message" => $this->dbConnect->error
            ];
        }
    }

    private function validateData() {
        $errors = [];

        if (!isset($this->email) || !filter_var($this->email, FILTER_VALIDATE_EMAIL) || strlen($this->email) <= 5) {
            $errors['email'] = 'Invalid email';
        }

        if (!isset($this->password) || strlen($this->password) <= 8 || !preg_match('/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/', $this->password)) {
            $errors['password'] = 'Invalid password';
        }

        if (!isset($this->repit_password) || $this->repit_password !== $this->password) {
            $errors['repit_password'] = 'Passwords do not match';
        }

        if (isset($this->phone_number) && strlen($this->phone_number) <= 5) {
            $errors['phone_number'] = 'Invalid phone number';
        }

        if (!isset($this->name) || !ctype_alpha($this->name)) {
            $errors['name'] = 'Invalid name';
        }

        $allowed_sources = ['site', 'city', 'tv', 'others'];
        if (isset($this->came_from) && !in_array($this->came_from, $allowed_sources)) {
            $errors['came_from'] = 'Invalid source';
        }

        if (!isset($this->date_birth)) {
            $errors['date_birth'] = 'Date of birth is required';
        } else {
            $date_birth = new DateTime($this->date_birth);
            $today = new DateTime();
            $age = $date_birth->diff($today)->y;
            if ($age <= 15 || $age >= 67) {
                $errors['date_birth'] = 'Invalid age';
            }
        }

        return $errors;
    }
}
