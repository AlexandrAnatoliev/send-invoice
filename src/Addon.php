<?php

declare(strict_types=1);

namespace sendInvoice;

use JsonSerializable;

/**
 * Addon represents an add-on product card for the order calculator.
 *
 * Each addon includes a name, price,
 * and an image path. It can render itself as an HTML checkbox label
 *
 * @package sendInvoice
 */
class Addon extends Card implements JsonSerializable
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
   * Render the addon as HTML label containing a checkbox input.
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

  /**
   * Specify data which should be serialized to JSON
   *
   * Called automatically by json_encode() when encoding instance 
   * of this class.
   *
   * @@return array
   */ 
  public function jsonSerialize(): array
  {
    return [
      'name'        => $this->getName(),
      'priceTiers'  => $this->getPriceTiers(),
    ];
  }
}
