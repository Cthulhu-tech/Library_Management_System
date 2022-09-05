<?php

require "vendor/autoload.php";

require_once __DIR__ . '/../../interface/interface.php';

class Env implements IEnv
{
    public function getEnv(): void
    {

        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../../');
        $dotenv->load();
    }
}