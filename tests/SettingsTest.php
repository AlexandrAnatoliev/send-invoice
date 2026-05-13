<?php

declare(strict_types=1);

namespace Tests;

require_once __DIR__ . '/../vendor/autoload.php';
use PHPUnit\Framework\TestCase;
use sendInvoice\Settings;

class SettingsTest extends TestCase
{
    private Settings $settings;

    protected function setUp(): void
    {
        $this->settings = new Settings(
            ['testSetting' => 'test',],
        );
    }

    public function testSettingsConstructor(): void
    {
        $this->assertSame('test', $this->settings->get('testSetting'));
    }
}
