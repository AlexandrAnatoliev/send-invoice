<?php

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/utils/session.php';
require_once __DIR__ . '/utils/mailer.php';
require_once __DIR__ . '/utils/generatePDF.php';

use sendInvoice\Invoice;
use sendInvoice\Config;

$location = 'Location: index.html';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header($location);
  exit;
}

$sourcePath     = htmlspecialchars($_POST['source_path'] ?? '');
$customerName   = htmlspecialchars($_POST['customer_name'] ?? '');
$customerPhone  = htmlspecialchars($_POST['customer_phone'] ?? '');
$itemNameKey    = htmlspecialchars($_POST['itemName'] ?? '');
$quantity       = (int) ($_POST['quantity'] ?? '');
$selectedAddons = $_POST['selectedAddons'] ?? [];
$customerEmail  = htmlspecialchars($_POST['customer_email'] ?? '');

$config = new Config();

$items = $_SESSION['items_session'] ?? [];

if ($items === []) {
  header($location);
  exit;
}

$addons = $_SESSION['addons_session'];

$selectedItem = null;
foreach ($items as $item) {
  if ($item->getName() === $itemNameKey) {
    $selectedItem = $item;
    break;
  }
}

if ($selectedItem === null) {
  header($location);
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
