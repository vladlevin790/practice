<?php

namespace src\Classes;

class Database
{
    public $dbConnect;

    public function __construct(string $host, string $user, string $password, string $db){
        $this->dbConnect = new mysqli();
    }

}