<?php

declare(strict_types=1);

namespace Tests;

require_once __DIR__ . '/../vendor/autoload.php';
use PHPUnit\Framework\TestCase;
use sendInvoice\Addon;

class AddonTest extends TestCase
{
    private Addon $addon;

    protected function setUp(): void
    {
        $this->addon = new Addon(
            'Тестовый товар',
            123456,
            'img/test.jpg',
        );
    }

    public function testGetAddonReturnsValidHtml(): void
    {
        $html = $this->addon->render();

        // Проверяем структуру label и наличие обязательных элементов
        $this->assertStringContainsString('<label class="card small">', $html);
        $this->assertStringContainsString('type="checkbox"', $html);
        $this->assertStringContainsString('name="addons[]"', $html);
        $this->assertStringContainsString('Тестовый товар', $html);
        $this->assertStringContainsString('123 456 ₽', $html); // number_format
        $this->assertStringContainsString('img/test.jpg', $html);

        // Проверяем data-атрибуты
        $this->assertStringContainsString('data-price="123456"', $html);
        $this->assertStringContainsString('data-name="' . htmlspecialchars($this->addon->getName()) . '"', $html);
    }

    public function testGetPriceWithoutQuantityReturnsBasePrice(): void
    {
        $this->assertSame(123456, $this->addon->getPrice());
    }

    public function testGetPriceWithUnknownQuantityFallsBackToBasePrice(): void
    {
        $this->assertSame(123456, $this->addon->getPrice(10));
        $this->assertSame(123456, $this->addon->getPrice(1));
    }

    public function testSetPriceTierChangesPriceForExactQuantity(): void
    {
        $this->addon->setPriceTier(10, 4000);
        $this->assertSame(4000, $this->addon->getPrice(10));

        // другие количества не затронуты
        $this->assertSame(123456, $this->addon->getPrice(5));
        $this->assertSame(123456, $this->addon->getPrice(50));
    }
}
