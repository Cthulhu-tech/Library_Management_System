<?php

require './src/interface/interface.php';
require './src/utils/env/env.php';
require './src/handler.php';

class Project implements Main
{
    private $env;
    private $handler;

    function Main()
    {
        $this->loadEnv();
        $this->handleUri();
    }

    private function loadEnv()
    {
        $this->env = new Env();
        $this->env->getEnv();
    }

    private function handleUri()
    {
        $this->handler = new Handler();
        $this->handler->handleMethod();
    }
}

$project = new Project();
$project->Main();