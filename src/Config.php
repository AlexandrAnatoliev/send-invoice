<?php

namespace sendInvoice;

class Config
{
    private array $env;

    /**
     * При создании инициализирует массив с настройками
     * либо из переменной окружения $_ENV
     * либо из тестового массива
     *
     * @param  $env - Тестовый массив
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
     * Возвращает значение настроек конфига по ключу
     *
     * @param  $key     - Ключ
     * @param  $default - Дефолтное значение (если ключ не найден)
     * @return Значение настроек конфига
     */
    public function get($key, $default = 'Заполните настройки'): string
    {
        return $this->env[$key] ?? getenv($key) ?: $default;
    }
}
