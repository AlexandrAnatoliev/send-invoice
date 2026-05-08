<?php

declare(strict_types=1);

namespace Tests;

require_once __DIR__ . '/../vendor/autoload.php';
use PHPUnit\Framework\TestCase;
use sendInvoice\Selector;

class SelectorTest extends TestCase
{
    private Selector $selector;

    protected function setUp(): void
    {
        $this->selector = new Selector(
            'Тестовый селектор',
            0,
            1000,
            50,
        );
    }

    public function testGetNameReturnsValidValue(): void
    {
        $name = $this->selector->getName();
        $this->assertStringContainsString('Тестовый селектор', $name);
    }

    public function testGetCSSReturnsValidStyleTag(): void
    {
        $cssTag = Selector::getCSS();
        $this->assertStringStartsWith('<style>', $cssTag);
        $this->assertStringEndsWith('</style>', $cssTag);
        $this->assertNotEmpty(trim(strip_tags($cssTag)));
    }

    public function testRenderReturnsValidHtml(): void
    {
        $html = $this->selector->render();

        // Проверяем структуру label и наличие обязательных элементов
        $this->assertStringContainsString('id="quantity"', $html);
        $this->assertStringContainsString('name="quantity"', $html);
    }
}
