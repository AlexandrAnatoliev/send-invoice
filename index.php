<?php

declare(strict_types=1);

require_once 'src/Card.php';
use sendInvoice\Card;

$card1 = new Card(
    'Ocean',
    16,
    'img/Card/ocean_pen.jpg',
);
$card2 = new Card(
    'Senator',
    19,
    'img/Card/senator_pen.jpg',
);
$card3 = new Card(
    'Lychee',
    15,
    'img/Card/lychee_pen.jpg',
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
              <?= Card::getCardCSS() ?>
              <?= $card1->getCard() ?>
              <?= $card2->getCard() ?>
              <?= $card3->getCard() ?>
            </div>
        </form>
    </div>
</body>
</html>
