<?php

namespace sendInvoice;

/**
 * Card represents a product card for the order calculator.
 *
 * Each card includes a name, price,
 * and an image path.
 *
 * @package sendInvoice
 */
class Invoice extends FormElement
{
    protected const CSS_FILE = '../styles/Invoice.css';

    /**
     * Create a new instance.
     *
     * @param $name  Product name
     */
    public function __construct(
        string $name,
    ) {
        parent::__construct($name);
    }

    /**
     * Render the invoice as an HTML page.
     *
     * @return HTML markup of the invoice
     */
    public function render(): string
    {
        $invoice  = $this->getCSS(); 

        $invoice .= '
  <div class="empty-line"></div>';

        $dateIn3Days = date('Y-m-d', strtotime('+3 days'));

        $invoice .= '
  <p>Оплатить не позднее ' . $dateIn3Days . '</p>

  <p>Оплата данного счёта означает согласие с условиями поставки товара.<br>
  Уведомление об оплате обязательно, в противном случае не гарантируется наличие товара на складе.<br>
  Товар отпускается по факту прхода денег на р/с Поставщика, самовывозом, при наличии доверенности и паспорта.<p>

  <div class="empty-line"></div>

  <div class="divider"></div>
  
  <table class="middle-table">
    <tr>
      <td><b>Предприниматель</b></td>
      <td>__________________________(' . $bankDetails['entrepreneurs_surname'] . ')</td>
    </tr>
  </table>';
        return $invoice;
    }
}
