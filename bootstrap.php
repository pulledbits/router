<?php
require __DIR__ . DIRECTORY_SEPARATOR . 'Resources.php';

return new class implements \rikmeijer\Teach\Resources
{
    private $resources;

    public function __construct() {

        require __DIR__ . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
        $this->resources = require __DIR__ . DIRECTORY_SEPARATOR . 'resources.php';
    }

    public function schema() : \pulledbits\ActiveRecord\SQL\Schema {
        /**
         * @var $factory \pulledbits\ActiveRecord\RecordFactory
         */
        $factory = new \pulledbits\ActiveRecord\RecordFactory(__DIR__ . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'activerecord');
        return new \pulledbits\ActiveRecord\SQL\Schema($factory, new \PDO($this->resources['DB_CONNECTION'] . ':host=' . $this->resources['DB_HOST'] . ';dbname=' . $this->resources['DB_DATABASE'], $this->resources['DB_USERNAME'], $this->resources['DB_PASSWORD'], array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\'')));
    }

    public function session() : \Aura\Session\Session {
        $session_factory = new \Aura\Session\SessionFactory;
        return $session_factory->newInstance($_COOKIE);
    }

    public function router() : \Aura\Router\RouterContainer {
        return new \Aura\Router\RouterContainer();
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

    public function phpview($templateIdentifier): \pulledbits\View\Template
    {
        return new pulledbits\View\File\Template(__DIR__ . "/resources/views/" . $templateIdentifier . '.php', __DIR__ . '/resources/layouts', __DIR__ . "/storage/views");
    }
};