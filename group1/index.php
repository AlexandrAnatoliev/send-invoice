<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../utils/session.php';

use sendInvoice\Item;
use sendInvoice\Addon;
use sendInvoice\Selector;

$item1 = new Item(
  name: 'Ocean1',
  price: 16,
  image: 'img/Item/ocean_pen.jpg',
);
$item2 = new Item(
  name: 'Senator1',
  price: 19,
  image: 'img/Item/senator_pen.jpg',
);
$item3 = new Item(
  name: 'Lychee1',
  price: 15,
  image: 'img/Item/lychee_pen.jpg',
);

$addon1 = new Addon(
  name: 'print_on_clip1',
  price: 46,
  image: 'img/Addon/print_on_clip.png',
);

$addon1->setPriceTier(quantity: 100, price: 46);
$addon1->setPriceTier(quantity: 200, price: 36);
$addon1->setPriceTier(quantity: 300, price: 34);
$addon1->setPriceTier(quantity: 500, price: 31);
$addon1->setPriceTier(quantity: 1000, price: 28);

$addon2 = new Addon(
  name: 'print_on_colored_case1',
  price: 43,
  image: 'img/Addon/print_on_colored_case.png',
);

$addon2->setPriceTier(quantity: 100, price: 43);
$addon2->setPriceTier(quantity: 200, price: 34);
$addon2->setPriceTier(quantity: 300, price: 31);
$addon2->setPriceTier(quantity: 500, price: 29);
$addon2->setPriceTier(quantity: 1000, price: 26);

$addon3 = new Addon(
  name: 'print_on_white_case1',
  price: 33,
  image: 'img/Addon/print_on_white_case.png',
);

$addon3->setPriceTier(quantity: 100, price: 33);
$addon3->setPriceTier(quantity: 200, price: 26);
$addon3->setPriceTier(quantity: 300, price: 24);
$addon3->setPriceTier(quantity: 500, price: 22);
$addon3->setPriceTier(quantity: 1000, price: 20);

$selector = new Selector(
  name: 'quantity',
  min: 0,
  max: 1000,
  step: 50,
);

$_SESSION['items_session'] = [$item1, $item2, $item3];
$_SESSION['addons_session'] = [
  $addon1->getName() => $addon1,
  $addon2->getName() => $addon2,
  $addon3->getName() => $addon3,
];
?>

<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <title>send-invoice: group1</title>
  <link rel="stylesheet" href="../styles/index.css">
</head>
<body>
  <div class="calculator">
    <h1>Калькулятор услуг</h1>
    <form id="orderForm" action="../checkout.php" method="post">
      <!-- Main product selection (radio) -->
      <h2>1. Выберите сувенир</h2>
      <div class="radio-group">
        <?= Item::getCSS() ?>
        <?= $item1->render() ?>
        <?= $item2->render() ?>
        <?= $item3->render() ?>
      </div>

      <!-- Add-ons (checkboxes) -->
      <h2>2. Выберите нанесение</h2>
      <div class="checkbox-group">
        <?= $addon1->render() ?>
        <?= $addon2->render() ?>
        <?= $addon3->render() ?>
      </div>

      <!-- Quantity -->
      <h2>3. Нужное количество</h2>
      <div class="quantity-block">
        <?= Selector::getCSS() ?>
        <?= $selector->render() ?>
      </div>

      <!-- Customer details -->
      <h2>4. Ваши данные для получения счёта на оплату на почту</h2>
      <input type="text" name="customer_name" placeholder="Наименование организации для счёта" required>
      <input type="email" name="customer_email" placeholder="Email для отправки счета" required>
      <input type="tel" name="customer_phone" placeholder="Телефон контакта" required>

      <!-- Source group path for checkout “back” link -->
      <input type="hidden" name="source_path"
        value="<?= htmlspecialchars($_SERVER['SCRIPT_NAME']) ?>">

      <button type="submit">Заказать и получить счёт на оплату на email</button>
    </form>

    <br>

    <div class="button">
      <a href="">Сбросить</a>
    </div>

    <br>

    <div class="button">
      <a href="../index.html" >Вернуться на главную страницу</a>
    </div>

  </div>
</body>
</html>

<script>
const addonPrices = <?= json_encode([
  $addon1->getName() => $addon1,
  $addon2->getName() => $addon2,
  $addon3->getName() => $addon3
], JSON_UNESCAPED_UNICODE) ?>;

// Получить цену аддона для заданного количества по логике из invoice.php
function getAddonPrice(addonKey, quantity) {
  if (!addonPrices[addonKey]) return 0;

  const priceTiers = addonPrices[addonKey].priceTiers;
  if (!priceTiers) return 0;

  const circulations = Object.keys(priceTiers)
    .map(Number)
    .sort((a,b) => a - b);

  let price = priceTiers[circulations[0]];

  for (const circ of circulations) {
    if (quantity >= circ) {
      price = priceTiers[circ];
    } else {
      break;
    }
  }
  return price;
}

// Обновить отображаемую цену в карточках всех аддонов
function updateAddonPricesDisplay() {
  const qtySelect = document.getElementById('quantity');
  if (!qtySelect) return;

  const qty = parseInt(qtySelect.value) || 50;

  const addonCards = document.querySelectorAll('.checkbox-group .card');

  addonCards.forEach(
    card => {
    const checkbox = card.querySelector('input[type="checkbox"]');
    if (!checkbox) return;

    const addonKey = checkbox.value;
    const price = getAddonPrice(addonKey, qty);

    const priceSpan = card.querySelector('.price');
    if (priceSpan) {
      priceSpan.textContent = price  + ' ₽';
    }
  });
}

document.addEventListener('DOMContentLoaded', function() {
  const qtySelect = document.getElementById('quantity');
  if (!qtySelect) return;

  // Функции инициализации
  updateAddonPricesDisplay();

  // Обработчик изменения количества
  qtySelect.addEventListener('change', function() {
    updateAddonPricesDisplay();
  });
});
</script>
