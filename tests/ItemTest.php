<?php

declare(strict_types=1);

namespace Tests;

require_once 'src/Item.php';
use PHPUnit\Framework\TestCase;
use sendInvoice\Item;

class ItemTest extends TestCase
{
    private Item $card;

    protected function setUp(): void
    {
        $this->card = new Item(
            'Тестовый товар',
            123456,
            'img/test.jpg',
        );
    }

    public function testGetItemReturnsValidHtml(): void
    {
        $html = $this->card->getItem();

        // Проверяем структуру label и наличие обязательных элементов
        $this->assertStringContainsString('<label class="card">', $html);
        $this->assertStringContainsString('type="radio"', $html);
        $this->assertStringContainsString('name="itemName"', $html);
        $this->assertStringContainsString('Тестовый товар', $html);
        $this->assertStringContainsString('123 456 ₽', $html); // number_format
        $this->assertStringContainsString('img/test.jpg', $html);

        // Проверяем data-атрибуты
        $this->assertStringContainsString('data-price="123456"', $html);
        $this->assertStringContainsString('data-name="' . htmlspecialchars($this->card->getName()) . '"', $html);
    }

    public function testDataNameShouldBeProductNameNotPrice(): void
    {
        $html = $this->card->getItem();
        // Дополнительно убедимся, что старый баг не вернулся
        $this->assertStringNotContainsString('data-name="123456"', $html);
        $this->assertStringContainsString('data-name="Тестовый товар"', $html);
    }

    public function testGetItemCSSReturnsValidStyleTag(): void
    {
        $cssTag = Item::getItemCSS();
        $this->assertStringStartsWith('<style>', $cssTag);
        $this->assertStringEndsWith('</style>', $cssTag);
        $this->assertNotEmpty(trim(strip_tags($cssTag)));
    }
}
