<?php

namespace sendInvoice;

/**
 * Represents an invoice.
 *
 * @package sendInvoice
 */
class Invoice extends FormElement
{
  protected const CSS_FILE = '../styles/Invoice.css';
  private Config $config;
  private string $customerPhone;
  private string $customerName;

  /**
   * Create a new instance.
   *
   * @param $name           Invoice name
   * @param $config         Settings config
   * @param $customerPhone  Customer phone
   * @param $customerName   Customer name
   */
  public function __construct(
    string $name,
    Config $config,
    string $customerPhone,
    string $customerName,
  ) {
    parent::__construct($name);
    $this->config = $config;
    $this->customerPhone = $customerPhone;
    $this->customerName = $customerName;
  }

  /**
   * Render the invoice as an HTML.
   *
   * @return HTML markup of the invoice
   */
  public function render(): string
  {
    $invoice  = $this->getCSS();

    $invoice .= $this->renderMainTable();
    $invoice .= $this->renderMiddleTable();

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
  public function renderMainTable(): string
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

  /**
   * Render the invoice number as string.
   *
   * @return Invoice number
   */
  public function getInvoiceNumber(): string
  {
    $months = ['января', 'февраля', 'марта', 'апреля', 'мая', 'июня',
      'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря'];

    $currentRussianDate = date('j') . ' ' . $months[date('n') - 1]
      . ' ' . date('Y') . ' г.';

    return 'Счет на оплату № Б-' . date('Ymd-His')
      . ' от ' . $currentRussianDate;
  }

  /**
   * Format phone number
   *
   * @param $customerPhone Phone number in '89261234567' format
   * @return Phone number in '+7 (926) 123-45-67' format
   */
  public function formatPhoneNumber(string $customerPhone): string
  {
    /* '89261234567' */
    $customerPhone = preg_replace('/\D/', '', $customerPhone);
    /* 89261234567 */
    $customerPhone = '+7' . substr($customerPhone, 1);
    /* +79261234567 */

    return sprintf(
      '+7 (%s) %s-%s-%s',
      substr($customerPhone, 2, 3),   // 926
      substr($customerPhone, 5, 3),   // 123
      substr($customerPhone, 8, 2),   // 45
      substr($customerPhone, 10, 2)   // 67
    );
    /* +7 (926) 123-45-67 */
  }

  /**
   * Render the Middle Table as an HTML markup.
   *
   * @return HTML markup
   */
  public function renderMiddleTable(): string
  {
    $middleTable = '
  <div class="empty-line"></div>

  <div class="invoice-header">
   ' . $this->getInvoiceNumber() . '
  </div>

  <div class="empty-line"></div>

  <div class="divider"></div>';

    $formatted = $this->formatPhoneNumber($this->customerPhone);

    $middleTable .= '
  <table class="middle-table">
    <tr>
      <td class="label-cell">Поставщик<br>(Исполнитель):</td>
      <td class="value-cell">'
        . $this->config->get('IP_FULL_NAME') . '</td>
    </tr>
    <tr>
      <td class="label-cell">Покупатель<br>(Заказчик):</td>
      <td class="value-cell">' . $this->customerName . ', тел: '
        . $formatted . '</td>
    </tr>
    <tr>
      <td class="label-cell">Основание:</td>
      <td class="value-cell">' . $this->config->get('PAYMENT_BASIS') . '</td>
    </tr>
  </table>';
    return $middleTable;
  }
}
