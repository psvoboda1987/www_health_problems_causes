<?php

declare(strict_types = 1);

namespace App;

use Nette\Bootstrap\Configurator;


final class Bootstrap
{
    public static function boot(): Configurator
    {
        define('ROOT_DIR', dirname(__DIR__));
        $configurator = new Configurator;

        $configurator->setTimeZone('Europe/Prague');
        $configurator->setTempDirectory(ROOT_DIR . '/temp');

        $configurator->createRobotLoader()
            ->addDirectory(__DIR__)
            ->register();

        $configurator->addConfig(ROOT_DIR . '/config/common.neon');
        $configurator->addConfig(ROOT_DIR . '/config/services.neon');
        $configurator->addConfig(ROOT_DIR . '/config/local.neon');

        define('ENV_PRODUCTION', ($_SERVER['REMOTE_ADDR'] ?? null) !== '127.0.0.1'
            || ($_SERVER['SERVER_NAME'] ?? null) !== 'localhost');

        if (ENV_PRODUCTION) {
            $configurator->addConfig(ROOT_DIR . '/config/prod.neon');
            $configurator->enableTracy(ROOT_DIR . '/log', 'psvoboda1987@gmail.com');
            $configurator->setDebugMode('secret@213.29.74.133'); // enable for your remote IP
            return $configurator;
        }

        $configurator->addConfig(ROOT_DIR . '/config/local.neon');
        $configurator->enableTracy(ROOT_DIR . '/log');
        return $configurator;
    }
}
