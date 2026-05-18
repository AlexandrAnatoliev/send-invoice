<?php

namespace sendInvoice;

class Config
{
    private array $env;

    /**
     * Initialises the settings array from the $_ENV environment
     * variable or from test array.
     *
     * @param  $env - Test array (optional)
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

    /**
     * Returns the configuration value for the given key.
     *
     * @param  $key     - Configuration key
     * @param  $default - Default value (used if the key is not found)
     * @return Configuration value
     */
    public function get(string $key, $default = 'Заполните настройки'): string
    {
        return $this->env[$key] ?? getenv($key) ?: $default;
    }
}
