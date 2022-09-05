<?php

require_once __DIR__ . '/../interface/interface.php';

class Middleware implements IMiddleware
{
    private $fn;
    private $nextValue = false;

    public function fn($callback)
    {

        if (is_callable($callback)) {
            $this->fn = $callback;
        } else {
            throw new Exception('Invalid function');
        }

        return $this;
    }

    public function handler($callback)
    {

        $this->nextValue = false;

        if (is_callable($callback)) {

            $callback(function () {

                $this->nextValue = true;
            });
        } else {
            throw new Exception('Invalid function');
        }

        if ($this->nextValue) {

            return call_user_func($this->fn);
        }

        return $this;
    }
}