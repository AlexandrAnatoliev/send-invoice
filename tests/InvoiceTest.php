<?php

declare(strict_types=1);

namespace Tests;

require_once __DIR__ . '/../vendor/autoload.php';
use PHPUnit\Framework\TestCase;
use sendInvoice\Invoice;
use sendInvoice\Config;

class InvoiceTest extends TestCase
{
  private Invoice $invoice;

  protected function setUp(): void
  {
    $testConfig = new Config(
      ['testSetting' => 'testValue',],
    );

    $this->invoice = new Invoice(
      'Тестовый счет',
      $testConfig,
    );
  }

  public function testConstructorFieldsIsValid(): void
  {
    $this->assertSame('Тестовый счет', $this->invoice->getName());
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
}
