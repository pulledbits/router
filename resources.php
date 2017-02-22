<?php
/**
 * Created by PhpStorm.
 * User: hameijer
 * Date: 22-2-17
 * Time: 9:25
 */
namespace ActiveRecord;

interface Resources
{
    public function schema(): \ActiveRecord\SQL\Schema;

    public function session(): \Aura\Session\Session;

    public function router(): \Aura\Router\RouterContainer;

    public function blade(): \duncan3dc\Laravel\BladeInstance;

    public function phpview($templateIdentifier) : \ActiveRecord\PHPView;

    public function readAssetStar();

    public function readAssetUnstar();
}