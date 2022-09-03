<?php

require __DIR__ . '/../utils/check/checkBook.php';
require_once __DIR__ . '/../utils/database/db.php';

class Book extends Database
{
    function __construct()
    {

        $this->checkBook = new BookCheck();
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
    public function getGanre()
    {

        $protected = $this->checkBook->checkGanre();

        if (!$protected) {

            $this->limitGanre();

            return false;
        }

        $this->ganreWithFilter();
    }

    private function limitGanre()
    {

        $this->createDatabase();

        $this->result = $this->getDB()->query('SELECT * FROM ganre LIMIT 5');

        $row = json_encode($this->result->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);

        $this->closeConnection();

        print_r($row);
    }

    private function ganreWithFilter()
    {

        $this->createDatabase();

        $this->result = $this->getDB()->prepare("SELECT * FROM ganre WHERE ganre LIKE ? LIMIT 5");

        $this->result->execute(array($this->checkBook->getGanre() . "%"));

        $row = json_encode($this->result->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);

        $this->closeConnection();

        print_r($row);
    }
}