<?php

require_once __DIR__ . '/../database/db.php';

class BookCheck extends Database
{
    private $endDate = '';
    private $startDate = '';
    private $bookName = '';
    private $bookCreator = '';
    private $bookCount = 0;
    private $bookGanre = '';

    function __construct()
    {

        parent::__construct();
    }

    public function checkBook()
    {
    }

    public function checkGanre()
    {

        if (isset($_GET['ganre'])) {

            $this->bookGanre = $_GET['ganre'];

            return true;
        }

        return false;
    }

    public function getGanre()
    {

        return $this->bookGanre;
    }
}