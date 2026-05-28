<?php

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/utils/session.php';
require_once __DIR__ . '/utils/mailer.php';

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

// Отправка покупателю (с PDF-вложением)
$resultCustomer = sendEmail(
    $customerEmail,
    $customerName,
    $invoice->getInvoiceNumber(),
    $invoice->getInvoiceNumber(),
//     $pdfContent,       // PDF-вложение
//     $pdfFilename
    $config
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
    <?= $invoice->render() ?>

    <br>
    <div class="button">
      <a href="<?= $sourcePath ?>" >Вернуться</a>
    </div>

  </div>
</body>
</html>
