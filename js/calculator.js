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

  const qty = Number.parseInt(qtySelect.value) || 50;

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
 * Initialization script that runs once the DOM is fully loaded.
 *
 * It performs the following:
 * - Locates the quantity selector (#quantity). If not present, exits.
 * - Calls {@link updateAddonPricesDisplay} to set initial add-on prices.
 * - Attaches a 'change' event listener to the quantity selector to
 *   recalculate and update add-on prices whenever the quantity is changed.
 *
 * @listens DOMContentLoaded
 * @requires updateAddonPricesDisplay
 */
document.addEventListener('DOMContentLoaded', function() {
  const qtySelect = document.getElementById('quantity');
  if (!qtySelect) return;

  updateAddonPricesDisplay();

  qtySelect.addEventListener('change', function() {
    updateAddonPricesDisplay();
  });
});
