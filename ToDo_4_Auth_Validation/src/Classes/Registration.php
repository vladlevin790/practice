<?php

namespace Classes;

use Classes\Database as DatabaseAlias;
use DateTime;

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

        parent::__construct("localhost:3307", "root", "", "AuthSchema");
    }

    public function registerUser() {
        $existingUserQuery = "SELECT COUNT(*) as count FROM user WHERE email = ?";
        $existingUserStatement = $this->dbConnect->prepare($existingUserQuery);
        $existingUserStatement->bind_param("s", $this->email);
        $existingUserStatement->execute();
        $existingUserResult = $existingUserStatement->get_result();
        $existingUserCount = $existingUserResult->fetch_assoc()['count'];

        if ($existingUserCount > 0) {
            return [
                "status" => false,
                "message" => "пользователь с такой почтой уже зарегистрирован"
            ];
        }

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
                    "message" => "Регистрация прошла успешно"
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
            $errors['email'] = 'неккоректная почта, должен содержать “@”, должен быть длиннее пяти символов.';
        }

        if (!isset($this->password) || strlen($this->password) <= 8 || !preg_match('/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/', $this->password)) {
            $errors['password'] = 'некорректный пароль, должен быть длиннее восьми символов, должен
содержать буквы и цифры.';
        }

        if (!isset($this->repit_password) || $this->repit_password !== $this->password) {
            $errors['repit_password'] = 'пароли не совпадают';
        }

        if (isset($this->phone_number) && strlen($this->phone_number) <= 5) {
            $errors['phone_number'] = 'ошибка в номере телефона, должен быть длиннее 5 символов.';
        }

        if (!isset($this->name) || !ctype_alpha($this->name)) {
            $errors['name'] = 'ошибка в имени, может содержать только буквы.';
        }

        $allowed_sources = ['site', 'city', 'tv', 'others'];
        if (isset($this->came_from) && !in_array($this->came_from, $allowed_sources)) {
            $errors['came_from'] = 'не известный источник';
        }

        if (!isset($this->date_birth)) {
            $errors['date_birth'] = 'нет даты рождения';
        } else {
            $date_birth = new DateTime($this->date_birth);
            $today = new DateTime();
            $age = $date_birth->diff($today)->y;
            if ($age <= 15 || $age >= 67) {
                $errors['date_birth'] = 'возраст должен быть больше 15 и меньше 67 лет';
            }
        }

        return $errors;
    }
}
