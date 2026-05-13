<?php

namespace sendInvoice;

class Settings
{
    public array $env;

    /**
     *
     *
     * @param  $env
     */
    public function __construct(
        array $env = null,
    ) {
        if ($env == null) {
            require_once 'vendor/autoload.php';

            $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
            $dotenv->load();

            $this->env = $_ENV;
        } else {
            $this->env = $env;
        }
    }
}
