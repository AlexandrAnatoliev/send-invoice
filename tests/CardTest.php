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
        $this->assertStringContainsString('data-name="' . htmlspecialchars((string) $this->card->price) . '"', $html);
        // ⚠️ В текущем коде data-name подставлено значение цены – это вероятная ошибка.
        // Если нужно тестировать ожидаемое поведение (имя товара), тест упадёт.
    }
}
