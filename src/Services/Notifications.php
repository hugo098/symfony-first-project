<?php

namespace App\Services;

class Notifications{

    private $email;

    public function __construct($email)
    {
        dump($email); die;
        $this->email = $email;
    }

    public function sendNotification(){

    }
}