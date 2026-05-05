<?php

declare(strict_types=1);

require_once 'src/Item.php';
use sendInvoice\Item;

$item1 = new Item(
    'Ocean',
    16,
    'img/Item/ocean_pen.jpg',
);
$item2 = new Item(
    'Senator',
    19,
    'img/Item/senator_pen.jpg',
);
$item3 = new Item(
    'Lychee',
    15,
    'img/Item/lychee_pen.jpg',
);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <title>send-invoice</title>
  <link rel="stylesheet" href="styles/index.css">
</head>
<body>
    <div class="calculator">
        <h1>Калькулятор услуг</h1>
        <form id="orderForm" action="checkout.php" method="post">
            <!-- Блок выбора основного тарифа (Радио) -->
            <h2>1. Выберите сувенир</h2>
            <div class="radio-group">
              <?= Item::getItemCSS() ?>
              <?= $item1->getItem() ?>
              <?= $item2->getItem() ?>
              <?= $item3->getItem() ?>
            </div>
        </form>
    </div>
</body>
</html>
