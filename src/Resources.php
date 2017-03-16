<?php
/**
 * Created by PhpStorm.
 * User: hameijer
 * Date: 22-2-17
 * Time: 9:25
 */
namespace rikmeijer\Teach;

interface Resources
{
    public function schema(): \pulledbits\ActiveRecord\SQL\Schema;

    public function session(): \Aura\Session\Session;


    public function phpview($templateIdentifier) : \pulledbits\View\Template;

    public function readAssetStar();

    public function readAssetUnstar();

    public function qrRenderer(int $width, int $height) : \BaconQrCode\Renderer\Image\Png;
    public function qrWriter(\BaconQrCode\Renderer\RendererInterface $renderer) : \BaconQrCode\Writer;

    public function iCalReader(string $uri) : \ICal;

}