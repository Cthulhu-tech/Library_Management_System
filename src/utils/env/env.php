<?

    require "../../../vendor/autoload.php";

    class Env {

        private $path = "./.env";

        function __construct(string $path = "./.env") {

            $this->path = $path;

        }

        public function getEnv() {

            $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
            $dotenv -> load();
            
        }

    }

?>
