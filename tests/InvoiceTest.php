<?php

declare(strict_types=1);

namespace Tests;

require_once __DIR__ . '/../vendor/autoload.php';
use PHPUnit\Framework\TestCase;
use sendInvoice\Invoice;

class InvoiceTest extends TestCase
{
    private Invoice $invoice;

    protected function setUp(): void
    {
        $this->invoice = new Invoice(
            'Тестовый счет',
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
          '<title>Счёт на оплату · банковские реквизиты</title>', 
          $html);
    }
}
