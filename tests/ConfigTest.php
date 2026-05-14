<?php

declare(strict_types=1);

namespace Tests;

require_once __DIR__ . '/../vendor/autoload.php';
use PHPUnit\Framework\TestCase;
use sendInvoice\Config;

class ConfigTest extends TestCase
{
    private Config $testConfig;
    private Config $envConfig;

    protected function setUp(): void
    {
        $this->testConfig = new Config(
            ['testSetting' => 'testValue',],
        );

        $this->envConfig = new Config();
    }

    public function testTestConfigConstructor(): void
    {
        $this->assertSame('testValue', $this->testConfig->get('testSetting'));
        $this->assertSame('Заполните настройки', $this->testConfig->get('noExistsSetting'));
    }

    public function testEnvConfigConstructor(): void
    {
        $this->assertSame('Заполните настройки', $this->envConfig->get('noExistsSetting'));
        $this->assertSame('Администратор заказов', $this->envConfig->get('ADMIN_NAME'));
    }
}
