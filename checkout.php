<?php

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';
require_once 'utils/selector.php';

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

$config = new Config();

$item1 = $_SESSION['item1_session'];
$item2 = $_SESSION['item2_session'];
$item3 = $_SESSION['item3_session'];

$itemArr[$item1->getName()] = $item1;
$itemArr[$item2->getName()] = $item2;
$itemArr[$item3->getName()] = $item3;

$selectedItem;
foreach ($itemArr as $item) {
  if ($item->getName() == $itemNameKey) {
    $selectedItem = $item;
  }
}
 
$invoice = new Invoice(
  'invoice',
  $config,
  $customerPhone,
  $customerName,
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

    <div class="button">
      <a href="<?= $sourcePath ?>" >Вернуться</a>
    </div>

  </div>
</body>
</html>
