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

    public function testSelectorReturnsValidValues(): void
    {
        $this->assertStringContainsString('Тестовый селектор', $this->selector->getName());
        $this->assertSame(0, $this->selector->getMin());
        $this->assertSame(1000, $this->selector->getMax());
        $this->assertSame(50, $this->selector->getStep());
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

        $this->assertStringContainsString('id="quantity"', $html);
        $this->assertStringContainsString('name="quantity"', $html);
        $this->assertStringContainsString('<option value=', $html);
    }
}
