<?php

namespace sendInvoice;

class Config
{
    private array $env;

    /**
     *
     *
     * @param  $env
     */
    public function __construct(?array $env = null)
    {
        if ($env == null) {
            $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '/../configs');
            $dotenv->load();

            $this->env = $_ENV;
        } else {
            $this->env = $env;
        }
    }

    public function get($key, $default = 'Заполните настройки'): string
    {
        return $this->env[$key] ?? getenv($key) ?: $default;
    }
}
