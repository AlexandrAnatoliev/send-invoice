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
    }

    public function testSetPriceTiersForAddon1ormIndex(): void
    {
        $this->addon->setPriceTier(100, 46);
        $this->addon->setPriceTier(200, 36);
        $this->addon->setPriceTier(300, 34);
        $this->addon->setPriceTier(500, 31);
        $this->addon->setPriceTier(1000, 28);
        $this->assertSame(46, $this->addon->getPrice(100));
        $this->assertSame(36, $this->addon->getPrice(200));
        $this->assertSame(34, $this->addon->getPrice(300));
        $this->assertSame(31, $this->addon->getPrice(500));
        $this->assertSame(28, $this->addon->getPrice(1000));
    }
}
