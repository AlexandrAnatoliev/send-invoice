<?php

declare(strict_types=1);

require_once 'src/Card.php';
use sendInvoice\Card;

$card = new Card();
$card->name = 'name1';
$card->css = 'styles/Card.css';
$card->price = 123;
$card->image = 'img/Card/ocean_pen.jpg';
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
              <?= $card->getCardCSS() ?>
              <?= $card->getCard() ?>
            </div>
        </form>
    </div>
</body>
</html>
