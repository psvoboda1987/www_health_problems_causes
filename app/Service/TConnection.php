<?php

declare(strict_types = 1);

namespace App\Service;

use Dibi\Connection;
use Dibi\Exception;
use Tracy\Debugger;

trait TConnection
{
    public bool $connected = false;
    
    public Connection $db;

    public function connect(): void
    {
        if ($this->connected) {
            return;
        }

        try {
            $this->db = new Connection(
                [
                    'driver' => 'mysqli',
                    'host' => 'localhost',
                    'username' => 'petr',
                    'password' => 'pokora127',
                    'database' => 'reporting',
                    'charset' => 'utf8',
                ]
            );
        } catch (Exception $exception) {
            bdump($exception->getMessage());
            Debugger::log($exception->getMessage());
        }

        $this->connected = true;
    }
}