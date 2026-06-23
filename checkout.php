<?php

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/utils/session.php';
require_once __DIR__ . '/utils/mailer.php';
require_once __DIR__ . '/utils/generatePDF.php';

use sendInvoice\Invoice;
use sendInvoice\Config;

define('REDIRECT_HEADER', 'Location: ');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header(REDIRECT_HEADER);
    exit;
}

if (!isset($_SESSION['csrf_token'])
  || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'] ?? '')) {
  http_response_code(403);
  die('Ошибка безопасности. Попробуйте отправить форму заново.');
}

unset($_SESSION['csrf_token']);

$sourcePath     = htmlspecialchars($_POST['source_path'] ?? '');

// Белый список разрешённых путей
$allowedPaths = [
    '/send-invoice/index.html',
    '/send-invoice/group1/',
    '/send-invoice/group2/',
    '/send-invoice/group1/index.php',
    '/send-invoice/group2/index.php',
];

// Если переданный путь не в белом списке – используем безопасное значение по умолчанию
if (!in_array($sourcePath, $allowedPaths, true)) {
    $sourcePath = '/send-invoice/index.html';
}

$customerName = htmlspecialchars($_POST['customer_name'] ?? '');

if (empty(trim($customerName))) {
    $_SESSION['error'] = 'Укажите наименование организации.';
    header('Location: ' . $sourcePath);
    exit;
}
$customerName   = htmlspecialchars($_POST['customer_name'] ?? '');

if (empty(trim($customerName))) {
    $_SESSION['error'] = 'Укажите наименование организации.';
    header('Location: ' . $sourcePath);
    exit;
}

$customerPhone  = htmlspecialchars($_POST['customer_phone'] ?? '');

$phoneDigits = preg_replace('/\D/', '', $customerPhone);
if (strlen($phoneDigits) < 10) {
    $_SESSION['error'] = 'Номер телефона должен содержать не менее 10 цифр.';
    header('Location: ' . $sourcePath);
    exit;
}

$itemNameKey    = htmlspecialchars($_POST['itemName'] ?? '');
$quantity       = (int) ($_POST['quantity'] ?? '');
if ($quantity < 0) {
    $_SESSION['error'] = 'Выберите корректное количество.';
    header('Location: ' . $sourcePath);
    exit;
}
$selectedAddons = $_POST['selectedAddons'] ?? [];
$email  = filter_input(INPUT_POST, 'customer_email', FILTER_VALIDATE_EMAIL);

if (!$email) {
    $_SESSION['error'] = 'Укажите корректный Email адрес.';
    header('Location: ' . $sourcePath);
    exit;
}

$customerEmail  = $email ? htmlspecialchars($email) : '';

$config = new Config();

$items = $_SESSION['items_session'] ?? [];

if ($items === []) {
    header(REDIRECT_HEADER);
    exit;
}

$addons = $_SESSION['addons_session'] ?? [];

$selectedItem = null;
foreach ($items as $item) {
    if ($item->getName() === $itemNameKey) {
        $selectedItem = $item;
        break;
    }
}

if ($selectedItem === null) {
    header(REDIRECT_HEADER);
    exit;
}

$invoice = new Invoice(
    'invoice',
    $config,
    $customerPhone,
    $customerName,
    $selectedItem,
    $quantity,
    $selectedAddons,
    $addons,
);

// Генерация PDF
$pdfContent = generatePDF($invoice->render());
$pdfFilename = "Счёт_" . date('Ymd-His') . ".pdf";

$emailContent  = "<h1>✓ Заказ оформлен!</h1>";
$emailContent .= "<p>Наш менеджер свяжется с вами дополнительно.</p>";

// Send to customer (PDF attachment optional — see utils/mailer.php)
$resultCustomer = sendEmail(
    $customerEmail,
    $customerName,
    $invoice->getInvoiceNumber(),
    $emailContent,
    $config,
    $pdfContent,
    $pdfFilename,
);

// Send copy to admin
$resultAdmin = sendEmail(
    $config->get('ADMIN_EMAIL'),
    $config->get('ADMIN_NAME'),
    "Копия: " . $invoice->getInvoiceNumber(),
    $emailContent,
    $config,
    $pdfContent,
    $pdfFilename,
);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <title>send-invoice: checkout</title>
  <link rel="stylesheet" href="styles/index.css">
</head>
<body>
  <div class="calculator">
    <h1>✓ Заказ оформлен!</h1>

    <div class="success-message">
      <p>Наш менеджер свяжется с вами дополнительно.</p><br>
      <p>Счет отправлен на <strong><?= htmlspecialchars($customerEmail) ?></strong>
      <?php if (!empty($customerPhone)) : ?>
          (<strong><?= htmlspecialchars($customerPhone) ?></strong>)
      <?php endif; ?></p>
      <p>Копия на <strong><?= htmlspecialchars($config->get('ADMIN_EMAIL')) ?></strong>
        (<strong><?= htmlspecialchars($config->get('ADMIN_NAME')) ?></strong>)</p>
    </div>

    <?php if (!$resultCustomer || !$resultAdmin) : ?>
      <div class="email-status email-error">
        <strong>⚠ Внимание!</strong> Письмо не было отправлено. Проверьте настройки почты.
      </div>
    <?php else : ?>
      <div class="email-status email-success">
        <strong>✓ Письмо успешно отправлено!</strong> Проверьте папку «Спам», если не видите письма.
      </div>
    <?php endif; ?>

    <?= $invoice->render() ?>

    <br>
    <div class="button">
      <a href="<?= $sourcePath ?>" >Вернуться</a>
    </div>

  </div>
</body>
</html>
