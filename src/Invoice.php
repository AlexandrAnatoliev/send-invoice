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
  private Item $selectedItem;
  private int $quantity;
  private array $selectedAddons;
  private array $addons;
  /**
   * Create a new instance.
   *
   * @param $name           Invoice name
   * @param $config         Settings config
   * @param $customerPhone  Customer phone
   * @param $customerName   Customer name
   * @param $selectedItem   Selected item
   * @param $quantity       Quantity of items
   * @param $selectedAddons Selected addon names
   * @param $addons         Addons array
   */
  public function __construct(
    string $name,
    Config $config,
    string $customerPhone,
    string $customerName,
    Item $selectedItem,
    int $quantity,
    array $selectedAddons,
    array $addons,
  ) {
    parent::__construct($name);
    $this->config = $config;
    $this->customerPhone = $customerPhone;
    $this->customerName = $customerName;
    $this->selectedItem = $selectedItem;
    $this->quantity = $quantity;
    $this->selectedAddons = $selectedAddons;
    $this->addons = $addons;
  }

  public function getItem(): Item
  {
    return $this->selectedItem;
  }

  public function getQuantity(): int
  {
    return $this->quantity;
  }

  public function getSelectedAddons(): array
  {
    return $this->selectedAddons;
  }

  public function getAddons(): array
  {
    return $this->addons;
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
    $invoice .= $this->renderItemsTable();

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

  public function renderItemsTable(): string 
  {
    $item = $this->getItem();

    $itemsTable = '
  <table class="items-table">
    <thead>
      <tr>
        <th class="col-right">№</th>
        <th class="col-left">Наименование товаров, работ, услуг</th>
        <th class="col-right">Кол-во</th>
        <th class="col-center">Ед. изм.</th>
        <th class="col-right">Цена</th>
        <th class="col-right">Всего</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td class="col-right">1</td>
        <td class="col-left">' . $this->selectedItem->getName() . '</td>
        <td class="col-right">' . $this->quantity . '</td>
        <td class="col-center">шт.</td>
        <td class="col-right">' . number_format(
          $this->selectedItem->getPrice(), 2, ',', ' ') . '</td>
        <td class="col-right">' . number_format(
          $this->selectedItem->getPrice() * $this->quantity, 2, ',', ' ')
          . '</td>
      </tr>';

    $total = $this->selectedItem->getPrice() * $this->quantity;
    $rowNumber = 1;

    foreach ($this->getSelectedAddons() as $addonKey) {
      if (isset($addons[$addonKey])) {
        $rowNumber++;
        $addonPrice = getPrice(
          $addon_prices,
          $addonKey,
          $this->quantity
        );
        $addonSum = $addonPrice * $this->quantity;
        $total += $addonSum;

        $itemsTable .= '
          <tr>
            <td class="col-right">' . $rowNumber . '</td>
            <td class="col-left">' . htmlspecialchars($addons[$addonKey]['name']) . '</td>
            <td class="col-right">' . $this->quantity . '</td>
            <td class="col-center">шт.</td>
            <td class="col-right">' . number_format($addonPrice, 2, ',', ' ') . '</td>
            <td class="col-right">' . number_format($addonSum, 2, ',', ' ') . '</td>
          </tr>';
      }
    }

    $itemsTable .= '
    </tbody>
    <tfoot>
      <tr>
        <td colspan="5" style="text-align:right; font-weight:bold;">Итого к оплате:</td>
        <td class="col-right" style="font-weight:bold;">' . number_format($total, 2, ',', ' ') . '</td>
      </tr>
      <tr>
        <td colspan="5" style="text-align:right; font-weight:bold;">Без налога (НДС)</td>
        <td class="col-right" style="font-weight:bold;">—</td>
      </tr>
    </tfoot>
  </table>';

    $totalInWords = $this->num2words($total);

    $itemsTable .= '
  <div class="empty-line"></div>

  <p>Всего наименований ' . $rowNumber . ', на сумму ' . number_format($total, 2, ',', ' ') . ' руб<br>
  <b>(' . $totalInWords . '</b>)</p>';

    return $itemsTable;
  }

  /**
   * Вспомогательная функция для склонения слов в зависимости от числа.
   *
   * @param  $n   - число, для которого нужно подобрать форму слова
   * @param  $f1  - форма для числа 1 (рубль, копейка)
   * @param  $f2  - форма для чисел 2-4 (рубля, копейки)
   * @param  $f5  - форма для чисел 5-20 и 0 (рублей, копеек)
   * @return      - одна из трёх форм слова в зависимости от числа
   */
  public function morph(
    int|string $n,
    string $f1,
    string $f2,
    string $f5
  ): string
  {
    $n = abs(intval($n)) % 100;
    $answer = $f5;

    if ($n > 10 && $n < 20) {
        return $answer;
    }
    $n = $n % 10;
    if ($n > 1 && $n < 5) {
        $answer = $f2;
    } elseif ($n == 1) {
        $answer = $f1;
    }
    return $answer;
  }

  /**
   * Преобразует число (сумму в рублях) в строку прописью.
   *
   * @param  $num - сумма, которую нужно преобразовать.
   *                Может быть числом с плавающей точкой
   *                (например, 1500.50) или строкой.
   * @return      - сумма прописью в формате:
   *                "одна тысяча пятьсот рублей 50 копеек"
   */
  public function num2words(float|int|string $num): string
  {
    $nul = 'ноль';
    $ten = [
        ['', 'один', 'два', 'три', 'четыре', 'пять', 'шесть', 'семь', 'восемь', 'девять'],
        ['', 'одна', 'две', 'три', 'четыре', 'пять', 'шесть', 'семь', 'восемь', 'девять'],
    ];
    $a20 = ['десять', 'одиннадцать', 'двенадцать', 'тринадцать', 'четырнадцать', 'пятнадцать', 'шестнадцать', 'семнадцать', 'восемнадцать', 'девятнадцать'];
    $tens = ['', '', 'двадцать', 'тридцать', 'сорок', 'пятьдесят', 'шестьдесят', 'семьдесят', 'восемьдесят', 'девяносто'];
    $hundred = ['', 'сто', 'двести', 'триста', 'четыреста', 'пятьсот', 'шестьсот', 'семьсот', 'восемьсот', 'девятьсот'];
    $unit = [
        ['копейка', 'копейки', 'копеек', 1],
        ['рубль', 'рубля', 'рублей', 0],
        ['тысяча', 'тысячи', 'тысяч', 1],
        ['миллион', 'миллиона', 'миллионов', 0],
        ['миллиард', 'миллиарда', 'миллиардов', 0],
    ];

    if (!is_numeric($num)) {
        return 'ноль рублей 00 копеек';
    }

    $num = round($num, 2);
    [$rub, $kop] = explode('.', sprintf("%015.2f", $num));

    $out = [];
    if (intval($rub) > 0) {
        foreach (str_split($rub, 3) as $uk => $v) {
            if (!intval($v)) {
                continue;
            }

            $uk = sizeof($unit) - $uk - 1;
            $gender = $unit[$uk][3];

            [$i1, $i2, $i3] = array_map('intval', str_split($v, 1));

            $out[] = $hundred[$i1];
            if ($i2 > 1) {
                $out[] = $tens[$i2] . ' ' . $ten[$gender][$i3];
            } else {
                $out[] = ($i2 > 0) ? $a20[$i3] : $ten[$gender][$i3];
            }

            if ($uk > 1) {
                $out[] = $this->morph($v, $unit[$uk][0], $unit[$uk][1], $unit[$uk][2]);
            }
        }
    } else {
        $out[] = $nul;
    }

    $out[] = $this->morph(intval($rub), $unit[1][0], $unit[1][1], $unit[1][2]);
    $out[] = $kop . ' ' . $this->morph($kop, $unit[0][0], $unit[0][1], $unit[0][2]);

    return trim(preg_replace('/ {2,}/', ' ', implode(' ', $out)));
  }
}
