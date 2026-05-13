<?php

namespace sendInvoice;

require_once 'vendor/autoload.php';
use Dotenv\Dotenv;

class Settings
{
    private array $env;

    /**
     *
     *
     * @param  $env
     */
    public function __construct(
        array $env = null,
    ) {
        if ($env == null) {
            $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__);
            $dotenv->load();

            $this->env = $_ENV;
        } else {
            $this->env = $env;
        }
    }

    public function get($key, $default = 'Заполните настройки'): string
    {
        if (array_key_exists($key, $this->env)) {
            return $this->env[$key];
        }
        return $default;
    }
}
