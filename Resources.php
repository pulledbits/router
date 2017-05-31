<?php namespace rikmeijer\Teach;

class Resources
{
    private $resourcesPath;

    public function __construct(string $resourcesPath)
    {
        $this->resourcesPath = $resourcesPath;
    }

    public function schema(): \pulledbits\ActiveRecord\SQL\Schema
    {
        /**
         * @var $factory \pulledbits\ActiveRecord\RecordFactory
         */
        $factory = new \pulledbits\ActiveRecord\RecordFactory($this->resourcesPath . DIRECTORY_SEPARATOR . 'activerecord');
        return new \pulledbits\ActiveRecord\SQL\Schema($factory, require $this->resourcesPath . DIRECTORY_SEPARATOR . 'pdo.php');
    }

    private function assetsDirectory()
    {
        return $this->resourcesPath . DIRECTORY_SEPARATOR . 'assets';
    }

    public function readAssetStar()
    {
        $image = $this->assetsDirectory() . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'star.png';
        return file_get_contents($image);
    }

    public function readAssetUnstar()
    {
        $image = $this->assetsDirectory() . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'unstar.png';
        return file_get_contents($image);
    }

    public function session(): \Aura\Session\Session
    {
        $session_factory = new \Aura\Session\SessionFactory;
        return $session_factory->newInstance($_COOKIE);
    }

    public function phpview(): \pulledbits\View\Template
    {
        $template = new \pulledbits\View\File\Template($this->resourcesPath . DIRECTORY_SEPARATOR . "views", $this->resourcesPath . DIRECTORY_SEPARATOR . 'layouts');
        $template->registerHelper('url', function (string $path, string ...$unencoded): string {
            $encoded = array_map('rawurlencode', $unencoded);
            $path = sprintf($path, ...$encoded);
            if (strpos($path, '?') === false) {
                $query = '';
            } else {
                list($path, $query) = explode('?', $path, 2);
            }
            return (string)\GuzzleHttp\Psr7\ServerRequest::getUriFromGlobals()->withPath($path)->withQuery($query);
        });

        $template->registerHelper('csrfToken',
            function () : string {
                $session_factory = new \Aura\Session\SessionFactory;
                $session = $session_factory->newInstance($_COOKIE);
                return $session->getCsrfToken()->getValue();
            });
        $template->registerHelper('form',
            function (string $method, string $submitValue, string $model): void {
                ?>
                <form method="<?= $this->escape($method); ?>">
                    <input type="hidden" name="__csrf_value" value="<?= $this->csrfToken(); ?>"/>
                    <?= $model; ?>
                    <input type="submit" value="<?= $this->escape($submitValue); ?>"/>
                </form>
                <?php
            });

        $template->registerHelper('qr', function(int $width, int $height, string $data) : void
        {
            $renderer = new \BaconQrCode\Renderer\Image\Png();
            $renderer->setHeight($width);
            $renderer->setWidth($height);
            $writer = new \BaconQrCode\Writer($renderer);
            print $writer->writeString($data);
        });
        return $template;
    }

    public function iCalReader(string $uri): \ICal
    {
        return new \ICal($uri);
    }

    private function reformatDateTime(string $datetime, string $format) : string {
        $datetime = new \DateTime($datetime);
        $datetime->setTimezone(new \DateTimeZone(ini_get('date.timezone')));
        return $datetime->format($format);
    }

    public function convertToYear(string $datetime) : string {
        return $this->reformatDateTime($datetime, 'Y');
    }

    public function convertToWeek(string $datetime) : string {
        return ltrim($this->reformatDateTime($datetime, 'W'), '0');
    }

    public function convertToSQLDateTime(string $datetime) : string {
        return $this->reformatDateTime($datetime, 'Y-m-d H:i:s');
    }
}