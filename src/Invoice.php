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
  private Config $config;

  /**
   * Create a new instance.
   *
   * @param $name   Invoice name
   * @param $config Settings config
   */
  public function __construct(
    string $name,
    Config $config,
  ) {
    parent::__construct($name);
    $this->config = $config;
  }

  /**
   * Render the invoice as an HTML page.
   *
   * @return HTML markup of the invoice
   */
  public function render(): string
  {
    $invoice  = $this->getCSS();

    $invoice .= $this->renderMainTable();

    $invoice .= '
  <div class="empty-line"></div>';

        $dateIn3Days = date('Y-m-d', strtotime('+3 days'));

        $invoice .= '
  <p>Оплатить не позднее ' . $dateIn3Days . '</p>

  <p>Оплата данного счёта означает согласие с условиями поставки товара.<br>
  Уведомление об оплате обязательно, в противном случае не гарантируется наличие товара на складе.<br>
  Товар отпускается по факту прихода денег на р/с Поставщика, самовывозом, при наличии доверенности и паспорта.</p>

  <div class="empty-line"></div>

  <div class="divider"></div>

  <table class="middle-table">
    <tr>
      <td><b>Предприниматель</b></td>
      <td>__________________________('
        . $this->config->get('ENTREPRENEURS_SURNAME') . ')</td>
    </tr>
  </table>';
    return $invoice;
  }

  /**
   * Render the Main Table as an HTML.
   *
   * @return HTML markup
   */
  function renderMainTable(): string
  {
    return '
  <!-- ПЕРВАЯ ТАБЛИЦА — банковские реквизиты -->
  <table class="main-table">
    <tr>
      <td class="cell-bank-name" style="border-bottom: none;">
        ' . $this->config->get('RECIPIENT_BANK') . '<br><br>
      </td>
      <td class="cell-bik-label" style="vertical-align: top;">БИК</td>
      <td class="cell-bik-value" style="border-bottom: none; vertical-align: top;">
        ' . $this->config->get('BANK_IDENTIFICATION_CODE') . '
      </td>
    </tr>
    <tr>
      <td class="cell-bank-name" style="border-top: none;">Банк получателя</td>
      <td class="cell-bik-label">Сч. №</td>
      <td class="cell-bik-value" style="border-top: none;">
        ' . $this->config->get('CORRESPONDENT_BANK_ACCOUNT') . '
      </td>
    </tr>
    <tr>
      <td class="cell-inn-kpp">
        <span class="inn-cell">ИНН ' . $this->config->get('INN') . '</span>
        <span class="kpp-cell">КПП </span>
      </td>
      <td class="cell-account-label" style="vertical-align: top;" rowspan="2">Сч. №</td>
      <td class="cell-account-value" style="vertical-align: top;" rowspan="2">
        ' . $this->config->get('RECIPIENTS_BANK_ACCOUNT') . '
      </td>
    </tr>
    <tr>
      <td class="cell-recipient">' . $this->config->get('IP_NAME') . '<br><br>Получатель</td>
    </tr>
  </table>';
  }
}
