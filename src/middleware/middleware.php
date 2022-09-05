<?php

require_once __DIR__ . '/../interface/interface.php';

class Middleware implements IMiddleware
{
    private $check = true;
    private $count = 0;

    private $fn;
    private $nextValue = array();

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

        array_push($this->nextValue, false);

        $this->count = is_array($this->nextValue) ? count($this->nextValue) : 0;

        if (is_callable($callback)) {

            $callback(function () {

                $this->nextValue[$this->count - 1] = true;
            });
        } else {
            throw new Exception('Invalid function');
        }

        for ($i = 0; $i <= $this->count; $i++) {

            if ($this->nextValue[$this->count] === null) {

                $this->check = false;

                break;
            }
        }

        if ($this->check) {

            return call_user_func($this->fn);
        }

        return $this;
    }
}