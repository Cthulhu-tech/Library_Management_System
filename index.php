<?php

use parallel\{Channel, Runtime};

$ch = new Channel();

require './src/interface/interface.php';
require './src/utils/env/env.php';
require './src/handler.php';

class Project implements IMain
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