<?php

require_once "autoload.php";
use Classes\Registration;

header('Content-type: text/plain; charset=utf-8');

//$email = isset($_POST['email']) ? $_POST['email'] : '';
//$password = isset($_POST['password']) ? $_POST['password'] : '';
//$repit_password = isset($_POST['repit_password']) ? $_POST['repit_password'] : '';
//$phone_number = isset($_POST['phone_number']) ? $_POST['phone_number'] : '';
//$name = isset($_POST['name']) ? $_POST['name'] : '';
//$came_from = isset($_POST['came_from']) ? $_POST['came_from'] : '';
//$date_birth = isset($_POST['date_birth']) ? $_POST['date_birth'] : '';
//
//$registration = new Registration($email, $password, $repit_password, $phone_number, $name, $came_from, $date_birth);
//$result = $registration->registerUser();
//return $result;

$testData = [
    'email' => 'test@example.com',
    'password' => 'test12346',
    'repit_password' => 'test12346',
    'phone_number' => '12343232324',
    'name' => 'testUser',
    'came_from' => 'site',
    'date_birth' => '2000-01-01'
];

$registration = new Registration(
    $testData['email'],
    $testData['password'],
    $testData['repit_password'],
    $testData['phone_number'],
    $testData['name'],
    $testData['came_from'],
    $testData['date_birth']
);

$result = $registration->registerUser();

echo json_encode($result, JSON_UNESCAPED_UNICODE);

//create table user(id int not null auto_increment primary key , email varchar(150), password varchar(300), phone_number varchar(150), name varchar(150), came_from varchar(30), date_birth date);
?>