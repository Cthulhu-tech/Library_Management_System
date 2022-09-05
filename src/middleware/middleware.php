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

        if (is_array($callback[0])) {

            for ($i = 0; $i < count($callback); $i++) {

                $this->set($callback[$i]);
            }

            $this->end();
            return $this;
        }

        $this->set($callback);
        $this->end();
        return $this;
    }

    private function end()
    {

        for ($i = 0; $i < $this->count; $i++) {

            if ($this->nextValue[$i] === false) {

                $this->check = false;

                break;
            }
        }

        if ($this->check) {

            call_user_func($this->fn);
        }
    }

    private function set($func)
    {
        array_push($this->nextValue, false);

        $this->count = count($this->nextValue);

        if (is_callable($func)) {

            $func(function () {

                $resolve = $this->count - 1;

                $this->nextValue[$resolve] = true;
            });
        } else {
            throw new Exception('Invalid function');
        }
    }
}