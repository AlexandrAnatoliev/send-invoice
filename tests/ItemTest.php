<?php

declare(strict_types=1);

namespace Tests;

require_once __DIR__ . '/../vendor/autoload.php';
use PHPUnit\Framework\TestCase;
use sendInvoice\Item;

class ItemTest extends TestCase
{
    private Item $item;

    protected function setUp(): void
    {
        $this->item = new Item(
            'Тестовый товар',
            123456,
            'img/test.jpg',
        );
    }

    public function testGetItemReturnsValidHtml(): void
    {
        $html = $this->item->getItem();

        // Проверяем структуру label и наличие обязательных элементов
        $this->assertStringContainsString('<label class="item">', $html);
        $this->assertStringContainsString('type="radio"', $html);
        $this->assertStringContainsString('name="itemName"', $html);
        $this->assertStringContainsString('Тестовый товар', $html);
        $this->assertStringContainsString('123 456 ₽', $html); // number_format
        $this->assertStringContainsString('img/test.jpg', $html);

        // Проверяем data-атрибуты
        $this->assertStringContainsString('data-price="123456"', $html);
        $this->assertStringContainsString('data-name="' . htmlspecialchars($this->item->getName()) . '"', $html);
    }

    public function testDataNameShouldBeProductNameNotPrice(): void
    {
        $html = $this->item->getItem();
        // Дополнительно убедимся, что старый баг не вернулся
        $this->assertStringNotContainsString('data-name="123456"', $html);
        $this->assertStringContainsString('data-name="Тестовый товар"', $html);
    }

    public function testGetItemCSSReturnsValidStyleTag(): void
    {
        $cssTag = Item::getCardCSS();
        $this->assertStringStartsWith('<style>', $cssTag);
        $this->assertStringEndsWith('</style>', $cssTag);
        $this->assertNotEmpty(trim(strip_tags($cssTag)));
    }

    public function testGetCardRendersWithItemClassNotCard(): void
    {
        $html = $this->item->getItem();

        $this->assertStringNotContainsString(
            'class="card"',
            $html,
            'HTML still contains old CSS class "card" after rename to Item.',
        );
        $this->assertStringContainsString(
            'class="item"',
            $html,
            'HTML must use CSS class "item" matching the renamed class.',
        );
    }

    public function testGetItemCSSReturnsStyleTagWithContent(): void
    {
        $css = Item::getCardCSS();

        $this->assertStringStartsWith('<style>', $css);
        $this->assertStringEndsWith('</style>', $css);

        $inner = substr($css, 7, -8);
        $this->assertNotEmpty($inner, 'CSS content should not be empty when the file exists.');
    }
}
