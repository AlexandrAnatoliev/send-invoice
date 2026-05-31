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

    $this->assertStringContainsString('<label class="card small">', $html);
    $this->assertStringContainsString('type="checkbox"', $html);
    $this->assertStringContainsString('name="selectedAddons[]"', $html);
    $this->assertStringContainsString('Тестовый товар', $html);
    $this->assertStringContainsString('123 456 ₽', $html); // number_format
    $this->assertStringContainsString('img/test.jpg', $html);
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

  public function testSetPriceTiersForAddon1inIndex(): void
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

  public function testSetPriceTiersForAddon2inIndex(): void
  {
    $this->addon->setPriceTier(100, 43);
    $this->addon->setPriceTier(200, 34);
    $this->addon->setPriceTier(300, 31);
    $this->addon->setPriceTier(500, 29);
    $this->addon->setPriceTier(1000, 26);
    $this->assertSame(43, $this->addon->getPrice(100));
    $this->assertSame(34, $this->addon->getPrice(200));
    $this->assertSame(31, $this->addon->getPrice(300));
    $this->assertSame(29, $this->addon->getPrice(500));
    $this->assertSame(26, $this->addon->getPrice(1000));
  }

  public function testSetPriceTiersForAddon3inIndex(): void
  {
    $this->addon->setPriceTier(100, 33);
    $this->addon->setPriceTier(200, 26);
    $this->addon->setPriceTier(300, 24);
    $this->addon->setPriceTier(500, 22);
    $this->addon->setPriceTier(1000, 20);
    $this->assertSame(33, $this->addon->getPrice(100));
    $this->assertSame(26, $this->addon->getPrice(200));
    $this->assertSame(24, $this->addon->getPrice(300));
    $this->assertSame(22, $this->addon->getPrice(500));
    $this->assertSame(20, $this->addon->getPrice(1000));
  }

  public function testGetPriceTiers(): void
  {
    $this->addon->setPriceTier(100, 33);
    $this->addon->setPriceTier(200, 26);
    $this->addon->setPriceTier(300, 24);
    $this->addon->setPriceTier(500, 22);
    $this->addon->setPriceTier(1000, 20);
    $this->assertSame(
      [
        100 => 33,
        200 => 26,
        300 => 24,
        500 => 22,
        1000 => 20,
      ], $this->addon->getPriceTiers());
  }

  public function testJsonSerialize(): void
  {
    $this->addon->setPriceTier(100, 33);
    $this->addon->setPriceTier(200, 26);
    $this->addon->setPriceTier(300, 24);
    $this->addon->setPriceTier(500, 22);
    $this->addon->setPriceTier(1000, 20);
    $this->assertSame(
      [
        'name' => 'Тестовый товар',
        'priceTiers' => [
          100 => 33,
          200 => 26,
          300 => 24,
          500 => 22,
          1000 => 20,
        ]
      ], $this->addon->jsonSerialize());
  } 
}
