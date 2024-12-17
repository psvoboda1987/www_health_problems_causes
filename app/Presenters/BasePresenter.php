<?php

declare(strict_types = 1);

namespace App\Presenters;

use Nette;
use Nette\Utils\FileSystem;


abstract class BasePresenter extends Nette\Application\UI\Presenter
{
    public function renderDeleteCache(): never
    {
        FileSystem::delete(__DIR__ . '/../../temp/cache');
        die(200);
    }
}
