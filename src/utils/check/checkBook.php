<?php

require_once __DIR__ . '/../database/db.php';

class BookCheck extends Database
{
    private $endDate = '';
    private $startDate = '';
    private $bookDateCreated = '';
    private $bookName = '';
    private $bookCreator = '';
    private $bookCount = 0;
    private $bookGanre = '';

    function __construct()
    {

        parent::__construct();
    }

    public function checkBookAll()
    {

        $data = json_decode(file_get_contents('php://input'), true);

        if (isset($data['book'], $data['ganre'], $data['years'], $data['count'], $data['creator'])) {

            if (!is_int($data['years']) || !is_int($data['count'])) {

                return false;
            }

            $this->bookName = $data['book'];
            $this->bookGanre = $data['ganre'];
            $this->bookCount = $data['count'];
            $this->bookCreator = $data['creator'];
            $this->bookDateCreated = $data['years'];

            return true;
        }

        return false;
    }

    public function checkBook()
    {

        if (isset($_GET['book'])) {

            $this->bookName = $_GET['book'];

            return true;
        }

        return false;
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

    public function getName()
    {

        return $this->bookName;
    }

    public function getCount()
    {

        return $this->bookCount;
    }

    public function getCreator()
    {

        return $this->bookCreator;
    }

    public function getDateCreated()
    {

        return $this->bookDateCreated;
    }
}