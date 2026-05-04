<?php

declare(strict_types=1);

namespace Tests;

require_once 'src/Card.php';
use PHPUnit\Framework\TestCase;
use sendInvoice\Card;

class CardTest extends TestCase
{
    private Card $card;

    protected function setUp(): void
    {
        $this->card = new Card();
        $this->card->name = 'Тестовый товар';
        $this->card->price = 123456;
        $this->card->image = 'img/test.jpg';
        $this->card->css = __DIR__ . '/../styles/Card.css';
    }

    public function testGetCardReturnsValidHtml(): void
    {
        $html = $this->card->getCard();

        // Проверяем структуру label и наличие обязательных элементов
        $this->assertStringContainsString('<label class="card">', $html);
        $this->assertStringContainsString('type="radio"', $html);
        $this->assertStringContainsString('name="itemName"', $html);
        $this->assertStringContainsString('Тестовый товар', $html);
        $this->assertStringContainsString('123 456 ₽', $html); // number_format
        $this->assertStringContainsString('img/test.jpg', $html);

        // Проверяем data-атрибуты
        $this->assertStringContainsString('data-price="123456"', $html);
        $this->assertStringContainsString('data-name="' . htmlspecialchars($this->card->name) . '"', $html);
    }

    public function testDataNameShouldBeProductNameNotPrice(): void
    {
        $html = $this->card->getCard();
        // Дополнительно убедимся, что старый баг не вернулся
        $this->assertStringNotContainsString('data-name="123456"', $html);
        $this->assertStringContainsString('data-name="Тестовый товар"', $html);
    }

    public function testGetCardCSSWhenFileExists(): void
    {
        $this->assertFileExists($this->card->css);
        $cssTag = $this->card->getCardCSS();

        $this->assertStringStartsWith('<style>', $cssTag);
        $this->assertStringEndsWith('</style>', $cssTag);
        $this->assertNotEmpty(trim(strip_tags($cssTag))); // внутри есть CSS-код
    }

    public function testGetCardCSSWhenFileNotExists(): void
    {
        $this->card->css = '/nonexistent/file.css';
        $this->assertSame('<style></style>', $this->card->getCardCSS());
    }
}
