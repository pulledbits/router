<?php
require __DIR__. DIRECTORY_SEPARATOR . 'resources.php';
require __DIR__. DIRECTORY_SEPARATOR . 'PHPView.php';
return new class implements \ActiveRecord\Resources
{

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

    public function phpview($templateIdentifier): \ActiveRecord\PHPView
    {
        $cacheDirectory = __DIR__ . "/storage/views";

        return new class(__DIR__ . "/resources/views/" . $templateIdentifier . '.php', __DIR__ . '/resources/layouts') implements \ActiveRecord\PHPView {
            private $templatePath;
            private $layoutsPath;
            private $layout;

            public function __construct(string $templatePath, string $layoutsPath) {
                $this->templatePath = $templatePath;
                $this->layoutsPath = $layoutsPath;
            }

            private function sub(string $templateIdentifier) {
                return new self(dirname($this->templatePath) . DIRECTORY_SEPARATOR . $templateIdentifier . '.php', $this->layoutsPath);
            }

            public function render(array $variables) {
                extract($variables);
                include $this->templatePath;
                if ($this->layout !== null) {
                    $this->layout->render();
                }
            }

            public function layout(string $layoutIdentifier)
            {
                $this->layout = new class($this->layoutsPath . DIRECTORY_SEPARATOR . $layoutIdentifier . '.php') {
                    private $layoutPath;
                    private $sections;
                    private $currentSectionIdentifier;
                    public function __construct(string $layoutPath)
                    {
                        $this->layoutPath = $layoutPath;
                        $this->sections = [];
                        ob_start();
                    }

                    public function section(string $sectionIdentifier, string $content = null) {
                        if ($content !== null) {
                            $this->sections[$sectionIdentifier] = $content;
                            return;
                        } elseif ($this->currentSectionIdentifier !== null) {
                            $this->sections[$this->currentSectionIdentifier] = ob_get_clean();
                        }

                        $this->currentSectionIdentifier = $sectionIdentifier;
                        ob_start();
                    }

                    public function render() {
                        if ($this->currentSectionIdentifier !== null) {
                            $this->sections[$this->currentSectionIdentifier] = ob_get_clean();
                        }

                        include $this->layoutPath;
                    }

                    private function harvest(string $sectionIdentifier) {
                        return $this->sections[$sectionIdentifier];
                    }
                };
                return $this->layout;
            }
        };
    }
};