<?php

require_once __DIR__ . '/../database/db.php';

class BookCheck extends Database
{
    private $bookId = '';
    private $bookDateCreated = '';
    private $bookName = '';
    private $bookCreator = '';
    private $bookCount = 0;
    private $bookGanre = '';

    function __construct()
    {

        parent::__construct();
    }

    public function checkBookUpdate()
    {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!isset($data['id']) && !isset($data['book'], $data['ganre'], $data['years'], $data['count'], $data['creator'])) {

            return false;
        }

        if (isset($data['id'])) {
            $this->bookId = $data['id'];
        }

        if (isset($data['book'])) {
            $this->bookName = $data['book'];
        }

        if (isset($data['ganre'])) {
            $this->bookGanre = $data['ganre'];
        }

        if (isset($data['years'])) {
            $this->bookDateCreated = $data['years'];
        }

        if (isset($data['count'])) {
            $this->bookCount = $data['count'];
        }

        if (isset($data['creator'])) {
            $this->bookCreator = $data['creator'];
        }

        return true;
    }

    public function checkBookAll(bool $check)
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

            if ($check && !isset($data['id']) || !is_int($data['id'])) {

                return false;
            }

            if ($check && isset($data['id']) || is_int($data['id'])) {

                $this->bookId = $data['id'];
                return true;
            }

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

    public function getId()
    {
        return $this->bookId;
    }
}