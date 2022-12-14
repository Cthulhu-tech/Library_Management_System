<?php

require_once __DIR__ . '/../database/db.php';
require_once __DIR__ . '/../../interface/interface.php';

class BookCheck extends Database implements IBookCheck
{
    private $bookId = 0;
    private $bookDateCreated = '';
    private $bookName = '';
    private $bookCreator = '';
    private $bookCount = 0;
    private $bookGanre = '';

    function __construct()
    {

        parent::__construct();
    }

    public function checkBookId(): bool
    {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!isset($data['id'])) {

            echo $this->messageResponse(400, 'all fields are required');

            return false;
        }

        $this->bookId = $data['id'];

        return true;
    }

    public function checkBookUpdate(): bool
    {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!isset($data['id']) && !isset($data['book'], $data['ganre'], $data['years'], $data['count'], $data['creator'])) {

            echo $this->messageResponse(400, 'all fields are required');

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

    public function checkBookAll(bool $check): bool
    {

        $data = json_decode(file_get_contents('php://input'), true);

        if (isset($data['book'], $data['ganre'], $data['years'], $data['count'], $data['creator'])) {

            if (!is_int($data['years']) || !is_int($data['count'])) {

                echo $this->messageResponse(400, 'all fields are required');

                return false;
            }

            $this->bookName = $data['book'];
            $this->bookGanre = $data['ganre'];
            $this->bookCount = $data['count'];
            $this->bookCreator = $data['creator'];
            $this->bookDateCreated = $data['years'];

            if ($check && !isset($data['id']) || !is_int($data['id'])) {

                echo $this->messageResponse(400, 'all fields are required');

                return false;
            }

            if ($check && isset($data['id']) || is_int($data['id'])) {

                $this->bookId = $data['id'];

                return true;
            }

            return true;
        }

        echo $this->messageResponse(400, 'all fields are required');

        return false;
    }

    public function checkBook(): bool
    {

        if (isset($_GET['book'])) {

            $this->bookName = $_GET['book'];

            return true;
        }

        echo $this->messageResponse(400, 'all fields are required');

        return false;
    }

    public function checkGanre(): bool
    {

        if (isset($_GET['ganre'])) {

            $this->bookGanre = $_GET['ganre'];

            return true;
        }

        echo $this->messageResponse(400, 'all fields are required');

        return false;
    }

    public function getGanre(): string
    {

        return $this->bookGanre;
    }

    public function getName(): string
    {

        return $this->bookName;
    }

    public function getCount(): int
    {

        return $this->bookCount;
    }

    public function getCreator(): string
    {

        return $this->bookCreator;
    }

    public function getDateCreated(): int
    {
        return $this->bookDateCreated;
    }

    public function getId(): int
    {
        return $this->bookId;
    }
}