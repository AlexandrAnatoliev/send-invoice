<?php

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';
require_once 'utils/session.php';

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
$quantity       = (int) htmlspecialchars($_POST['quantity'] ?? '');
$selectedAddons = $_POST['addons'] ?? [];

$config = new Config();

$items = $_SESSION['items_session'];
$addons = $_SESSION['addons_session'];

$selectedItem = null;
foreach ($items as $item) {
  if ($item->getName() == $itemNameKey) {
    $selectedItem = $item;
    break;
  }
}

if ($selectedItem == null) {
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
    <?= $invoice->render() ?>

    <br>
    <div class="button">
      <a href="<?= $sourcePath ?>" >Вернуться</a>
    </div>

  </div>
</body>
</html>
