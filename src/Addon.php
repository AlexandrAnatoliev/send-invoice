<?php

namespace sendInvoice;

/**
 * Addon represents a addon product card for the order calculator.
 *
 * Each addon includes a name, price,
 * and an image path. It can render itself as an HTML checkbox-button label
 * and provides static CSS injection.
 *
 * @package sendInvoice
 */
class Addon extends Card
{
  private array $priceTiers = [];

  /**
   * Define a price tier for a specific quantity
   *
   * @param  $quantity  Exact number of items
   * @param  $price     Unit price for this quantity
   */
  public function setPriceTier(int $quantity, int $price): void
  {
    $this->priceTiers[$quantity] = $price;
  }

  /**
   * Get price based on quantity
   *
   * @param  $quantity - quantity of items
   * @return price
   */
  public function getPrice($quantity = null): int
  {
    if ($quantity === null) {
      return $this->price;
    }

    if (empty($this->priceTiers)) {
      return $this->price;
    }

    $price = $this->price;
    $tiers = $this->priceTiers;
    ksort($tiers);

    foreach ($tiers as $tiersQuantity => $priceValue) {
      if ($quantity <= $tiersQuantity) {
        $price = $priceValue;
        break;
      }
    }
    return $price;
  }

  /**
   * Render the addon as an HTML label containing a checkbox input.
   *
   * The check-box element carries data-price (raw integer) and data-name
   * (HTML-escaped) attributes for JavaScript consumption. The visible
   * price is formatted with a thousands separator and the Ruble sign.
   *
   * @return HTML markup of the addon
   */
  public function render(): string
  {
    return '
          <label class="card small">
            <input type="checkbox" name="addons[]"
                    value="' . htmlspecialchars($this->getName()) . '"
                    data-price="' . htmlspecialchars($this->getPrice()) . '"
                    data-name="' . htmlspecialchars($this->getName()) . '">
            <img src="' . htmlspecialchars($this->getImage()) 
              . '" alt="' . htmlspecialchars($this->getName()) . '">
            <span class="title">' . htmlspecialchars($this->getName()) . '</span>
            <span class="price">' . number_format($this->getPrice(), 0, ',', ' ') . ' ₽</span>
          </label>';
  }
}
