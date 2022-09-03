<?php

require __DIR__ . '/../utils/check/checkBook.php';
require_once __DIR__ . '/../utils/database/db.php';

class Book extends Database
{
    function __construct()
    {

        $this->checkUser = new UserCheck();
        parent::__construct();
    }

    public function getBook()
    {
    }
    public function setBook()
    {
    }
    public function updateBook()
    {
    }
    public function deleteBook()
    {
    }
}