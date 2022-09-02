<?php

require "vendor/autoload.php";

class Env
{
    public function getEnv()
    {

        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../../');
        $dotenv->load();
    }
}