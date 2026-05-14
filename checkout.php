<?php

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

use sendInvoice\Invoice;

$sourcePath = htmlspecialchars($_POST['source_path']) ?? '';
$invoice = new Invoice('invoice');
?>

<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <title>send-invoice: checkout</title>
  <link rel="stylesheet" href="styles/index.css">
</head>
<body>
  <?= $invoice->render() ?>
  <div class="calculator">
    <div class="button">
      <a href="<?= $sourcePath ?>" >Вернуться</a>
    </div>
  </div>
</body>
</html>
