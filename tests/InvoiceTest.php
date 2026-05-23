<?php

declare(strict_types=1);

namespace Tests;

require_once __DIR__ . '/../vendor/autoload.php';
use PHPUnit\Framework\TestCase;
use sendInvoice\Invoice;
use sendInvoice\Config;
use sendInvoice\Item;

class InvoiceTest extends TestCase
{
  private Invoice $invoice;

  protected function setUp(): void
  {
    $testConfig = new Config(
      ['testSetting' => 'testValue',],
    );

    $item = new Item(
      'Тестовый товар',
      123456,
      'img/test.jpg',
    );

    $this->invoice = new Invoice(
      'Тестовый счет',
      $testConfig,
      '89261234567',
      'Имя Покупателя',
      $item,
      123,
    );
  }

  public function testConstructorFieldsIsValid(): void
  {
    $this->assertSame('Тестовый счет', $this->invoice->getName());
    $this->assertSame(123, $this->invoice->getQuantity());
  }

  public function testGetCSSReturnsValidStyleTag(): void
  {
    $cssTag = Invoice::getCSS();
    $this->assertStringStartsWith('<style>', $cssTag);
    $this->assertStringEndsWith('</style>', $cssTag);
    $this->assertNotEmpty(trim(strip_tags($cssTag)));
  }

  public function testRenderReturnsValidHtml(): void
  {
    $html = $this->invoice->render();

    $this->assertStringContainsString(
      '<td><b>Предприниматель</b></td>',
      $html);
    $this->assertStringContainsString('.invoice-header {', $html);
  }

  public function testRenderMainTableReturnsValidHtml(): void
  {
    $html = $this->invoice->renderMainTable();

    $this->assertStringContainsString(
      '<table class="main-table">',
      $html);
  }

  public function testGetInvoiceNumber(): void
  {
    $html = $this->invoice->getInvoiceNumber();

    $this->assertStringContainsString(
      'Счет на оплату № Б-',
      $html);
  }

  public function testFormatPhoneNumber(): void
  {
    $phoneNumber = $this->invoice->formatPhoneNumber('89261234567');

    $this->assertStringContainsString(
      '+7 (926) 123-45-67',
      $phoneNumber);
  }

  public function testRenderMiddleTable(): void
  {
    $html = $this->invoice->renderMiddleTable();

    $this->assertStringContainsString('<table class="middle-table">',
      $html);
    $this->assertStringContainsString('+7 (926) 123-45-67', $html);
    $this->assertStringContainsString('Имя Покупателя', $html);
  }

  public function testRenderItemsTable(): void
  {
    $html = $this->invoice->renderItemsTable();

    $this->assertStringContainsString('<table class="items-table">',
      $html);
  }

  public function testMorph(): void
  {
    $num = $this->invoice->morph(
      "12",
      "рубль",
      "рубля",
      "рублей",
    );

    $this->assertSame("рублей", $num);
  }

  public function testNum2words(): void
  {
    $num = $this->invoice->num2words(1500.50);

    $this->assertSame("одна тысяча пятьсот рублей 50 копеек", $num);
  }

  public function testGetItem(): void
  {
    $this->assertSame('Тестовый товар',
      $this->invoice->getItem()->getName());
  }
}
