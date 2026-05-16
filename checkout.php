<?php

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

use sendInvoice\Invoice;
use sendInvoice\Config;

$sourcePath = htmlspecialchars($_POST['source_path']) ?? '';
$config = new Config();
$invoice = new Invoice(
  'invoice',
  $config,
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
