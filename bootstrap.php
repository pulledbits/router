<?php
return new class {

    /**
     * @var \duncan3dc\Laravel\BladeInstance
     */
    private $blade;

    public function __construct() {

        require __DIR__ . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
    }

    public function schema() : \ActiveRecord\SQL\Schema {
        $dotenv = new Dotenv\Dotenv(__DIR__);
        $dotenv->load();

        /**
         * @var $factory \ActiveRecord\RecordFactory
         */
        $factory = new \ActiveRecord\RecordFactory(__DIR__ . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'activerecord');
        return new \ActiveRecord\SQL\Schema($factory, new \PDO($_ENV['DB_CONNECTION'] . ':host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_DATABASE'], $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD'], array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\'')));
    }

    public function session() : \Aura\Session\Session {
        $session_factory = new \Aura\Session\SessionFactory;
        return $session_factory->newInstance($_COOKIE);
    }

    public function router() : \Aura\Router\RouterContainer {
        return new \Aura\Router\RouterContainer();
    }

    public function blade() : \duncan3dc\Laravel\BladeInstance
    {
        if ($this->blade === null) {
            $this->blade  = new \duncan3dc\Laravel\BladeInstance(__DIR__ . "/resources/views", __DIR__ . "/storage/views");
        }
        return $this->blade;
    }

    private function assetsDirectory() {
        return __DIR__ . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'assets';
    }

    public function readAssetStar() {
        $image = $this->assetsDirectory() . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'star.png';
        return file_get_contents($image);
    }
    public function readAssetUnstar() {
        $image = $this->assetsDirectory() . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'unstar.png';
        return file_get_contents($image);
    }
};