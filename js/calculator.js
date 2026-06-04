/**
 * Retrieves the price of a specific add-on for a given quantity.
 *
 * The price is determined by the add-on's tiered pricing structure 
 * (`priceTiers`). It iterates over sorted circulation thresholds 
 * and returns the price for the highest circulation that does not 
 * exceed the requested quantity.
 *
 * @param {string} addonKey - The unique key of the add-on 
 *        (matches the name used in `addonPrices`).
 * @param {number} quantity - The desired quantity of the item.
 * @returns {number} The calculated price for the add-on at the given
 *          quantity, or 0 if the add-on or its tiers are not found.
 *
 * @requires addonPrices - A global object (e.g., `window.addonPrices`) 
 *            containing add-on pricing data.
 * @example 
 * // getAddonPrice('print_on_clip1', 300) -> 34
 */
function getAddonPrice(addonKey, quantity) {
  if (!addonPrices[addonKey]) return 0;

  const priceTiers = addonPrices[addonKey];
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

/**
 * Updates the displayed price of each add-on card in the checkbox
 * group based on the currently selected quantity.
 *
 * This function reads the value of the <select id="quantity"> element,
 * retrieves the appropriate price for each add-on via 
 * {@link getAddonPrice}, and updates the `.price` span inside each 
 * `.checkbox-group .card` element.
 *
 * @requires getAddonPrice - Function to compute the add-on price 
 * for a given quantity.
 * @requires HTML element with id="quantity" - A <select> input 
 * for the desired quantity.
 *
 * @example
 * // Called after quantity change or page load:
 * updateAddonPricesDisplay();
 */
function updateAddonPricesDisplay() {
  const qtySelect = document.getElementById('quantity');
  if (!qtySelect) return;

  const qty = Number.parseInt(qtySelect.value) || 0;

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

/**
 * Calculates and displays the total order price.
 * 
 * Sums the main item price (unit × quantity) plus all selected add-ons
 * (tiered unit price × quantity). Updates the #totalPrice element with
 * the formatted result in Russian locale.
 * 
 * @returns {void}
 * 
 * @dependency getAddonPrice - For tiered pricing of add-ons
 * @dependency window.addonPrices - Global add-on pricing data
 * 
 * @requires DOM elements:
 *   - #quantity (select) - Selected quantity
 *   - #totalPrice (span) - Display target
 *   - input[name="itemName"]:checked - Selected main item
 *   - input[name="selectedAddons[]"]:checked - Selected add-ons
 */
function calculateTotal() {
  let total = 0;

  const qtySelect = document.getElementById('quantity');
  if (!qtySelect) return;

  const qty = Number.parseInt(qtySelect.value) || 0;

  const itemRadio = document.querySelector('input[name="itemName"]:checked');
  if (itemRadio) {
    total += (Number.parseFloat(itemRadio.dataset.price) || 0) * qty;
  }

  const checkedAddons = document.querySelectorAll('input[name="selectedAddons[]"]:checked');
  checkedAddons.forEach(cb => {
    const addonKey = cb.value;
    const unitPrice = getAddonPrice(addonKey, qty);
    total += unitPrice * qty;
  });

  const totalSpan = document.getElementById('totalPrice');
  if (totalSpan) {
    totalSpan.textContent = new Intl.NumberFormat('ru-RU').format(total);
  }
}

// Обновление списка выбранных позиций с динамическими ценами
function updateSelectedItems() {
    const selectedItems = [];
    const qty = parseInt(document.getElementById('quantity').value) || 50;

    // Выбранный тариф
    const itemRadio = document.querySelector('input[name="itemName"]:checked');
    if (itemRadio) {
        selectedItems.push({
            name: itemRadio.dataset.name,
            price: parseFloat(itemRadio.dataset.price) || 0
        });
    }

    // Выбранные аддоны
    const checkedAddons = document.querySelectorAll('input[name="addons[]"]:checked');
    checkedAddons.forEach(cb => {
        const addonKey = cb.value;
        const price = getAddonPrice(addonKey, qty);
        selectedItems.push({
            name: cb.dataset.name,
            price: price
        });
    });

    const selectedList = document.getElementById('selectedList');
    if (selectedItems.length === 0) {
        selectedList.innerHTML = '<li class="empty-selection">Ничего не выбрано</li>';
    } else {
        selectedList.innerHTML = selectedItems.map(item =>
            `<li>
                <span class="item-name">${item.name}</span>
                <span class="item-price">${new Intl.NumberFormat('ru-RU').format(item.price)} руб.</span>
            </li>`
        ).join('');
    }
}

/**
 * Initialization script that runs once the DOM is fully loaded.
 *
 * It performs the following:
 * - Locates the quantity selector (#quantity). If not present, exits.
 * - Calls {@link updateAddonPricesDisplay} to set initial add-on prices.
 * - Attaches a 'change' event listener to the quantity selector to
 *   recalculate and update add-on prices whenever the quantity is changed.
 * - Attaches event listeners to radio buttons and checkboxes to 
 *   recalculate total.
 *
 * @listens DOMContentLoaded
 * @requires updateAddonPricesDisplay
 */
document.addEventListener('DOMContentLoaded', function() {
  const qtySelect = document.getElementById('quantity');
  if (!qtySelect) return;

  updateAddonPricesDisplay();
  calculateTotal();
  updateSelectedItems();

  qtySelect.addEventListener('change', function() {
    updateAddonPricesDisplay();
    calculateTotal();
    updateSelectedItems();
  });

  const itemRadios = document.querySelectorAll('input[name="itemName"]');
  itemRadios.forEach(radio => {
    radio.addEventListener('change', function() {
      calculateTotal();
      updateSelectedItems();
    });
  });

  const addonCheckboxes = document.querySelectorAll('input[name="selectedAddons[]"]');
  addonCheckboxes.forEach(checkbox => {
    checkbox.addEventListener('change', function() {
      calculateTotal();
      updateSelectedItems();
    });
  });
});

