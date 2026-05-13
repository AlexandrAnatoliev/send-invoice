<?php

declare(strict_types=1);

namespace Tests;

require_once __DIR__ . '/../vendor/autoload.php';
use PHPUnit\Framework\TestCase;
use sendInvoice\Config;

class ConfigTest extends TestCase
{
    private Config $config;
    private Config $config2;

    protected function setUp(): void
    {
        $this->config = new Config(
            ['testSetting' => 'testValue',],
        );
        $this->config2 = new Config();
    }

    public function testConfigConstructor(): void
    {
        $this->assertSame('testValue', $this->config->get('testSetting'));
        $this->assertSame('Заполните настройки', $this->config->get('noExistsSetting'));
    }
}
