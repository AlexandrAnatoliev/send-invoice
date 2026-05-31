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
