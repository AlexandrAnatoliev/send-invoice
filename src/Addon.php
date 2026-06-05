<?php

declare(strict_types=1);

namespace sendInvoice;

/**
 * Addon represents an add-on product card for the order calculator.
 *
 * Each addon includes a name, price,
 * and an image path. It can render itself as an HTML checkbox label
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
   * Get price tiers
   *
   * @return array
   */
  public function getPriceTiers(): array
  {
    return $this->priceTiers;
  }

  /**
   * Get price based on quantity
   *
   * @param  ?int $quantity - quantity of items
   * @return int
   */
  public function getPrice($quantity = null): int
  {
    if ($quantity === null || $quantity === 0 || empty($this->priceTiers)) {
      return $quantity === 0 ? 0 : $this->price;
    }

    $tiers = $this->priceTiers;
    ksort($tiers);

    $price = $this->price;
    foreach ($tiers as $tiersQuantity => $priceValue) {
      if ($quantity <= $tiersQuantity) {
        return $priceValue;
      }
      $price = $priceValue;
    }

    return $price;
  }

  /**
   * Render the addon as HTML label containing a checkbox input.
   *
   * The check-box element carries data-price (raw integer) and data-name
   * (HTML-escaped) attributes for JavaScript consumption. The visible
   * price is formatted with a thousands separator and the Ruble sign.
   *
   * @return string
   */
  public function render(): string
  {
    return '
          <label class="card small">
            <input type="checkbox" name="selectedAddons[]"
                    value="' . htmlspecialchars($this->getName()) . '"
                    data-price="' . $this->getPrice() . '"
                    data-name="' . htmlspecialchars($this->getName()) . '">
            <img src="' . htmlspecialchars($this->getImage())
              . '" alt="' . htmlspecialchars($this->getName()) . '">
            <span class="title">' . htmlspecialchars($this->getName()) . '</span>
            <span class="price">' . number_format($this->getPrice(), 0, ',', ' ') . ' ₽</span>
          </label>';
  }
}
