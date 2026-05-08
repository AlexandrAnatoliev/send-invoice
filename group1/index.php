<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use sendInvoice\Item;
use sendInvoice\Addon;

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

$addon1 = new Addon(
    'print_on_clip',
    46,
    'img/Addon/print_on_clip.png',
);

$addon1->setPriceTier(100, 46);
$addon1->setPriceTier(200, 36);
$addon1->setPriceTier(300, 34);
$addon1->setPriceTier(500, 31);
$addon1->setPriceTier(1000, 28);

$addon2 = new Addon(
    'print_on_colored_case',
    43,
    'img/Addon/print_on_colored_case.png',
);

$addon2->setPriceTier(100, 43);
$addon2->setPriceTier(200, 34);
$addon2->setPriceTier(300, 31);
$addon2->setPriceTier(500, 29);
$addon2->setPriceTier(1000, 26);

$addon3 = new Addon(
    'print_on_white_case',
    33,
    'img/Addon/print_on_white_case.png',
);

$addon3->setPriceTier(100, 33);
$addon3->setPriceTier(200, 26);
$addon3->setPriceTier(300, 24);
$addon3->setPriceTier(500, 22);
$addon3->setPriceTier(1000, 20);
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
              <?= Item::getCSS() ?>
              <?= $item1->render() ?>
              <?= $item2->render() ?>
              <?= $item3->render() ?>
            </div>

            <!-- Блок дополнительных услуг (Чекбоксы) -->
            <h2>2. Выберите нанесение</h2>
            <div class="checkbox-group">
              <?= $addon1->render() ?>
              <?= $addon2->render() ?>
              <?= $addon3->render() ?>
            </div>
        </form>
    </div>
</body>
</html>
