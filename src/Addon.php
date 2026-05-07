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
    protected array $priceTiers = [];

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

        $price      = $this->price;
        $priceTiers = $this->priceTiers;

        foreach ($priceTiers as $prices => $priceValue) {
            $price = $priceValue;
            if ($priceValue > $quantity) {
                break;
            }
        }
        return $price;
    }

    /**
     * Render the addoon as an HTML label containing a checkbox input.
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
                    value="' . $this->getName() . '"
                    data-price="' . $this->getPrice() . '"
                    data-name="' . htmlspecialchars($this->getName()) . '">
            <img src="' . $this->getImage() . '" alt="' . $this->getName() . '">
            <span class="title">' . $this->getName() . '</span>
            <span class="price">' . number_format($this->getPrice(), 0, ',', ' ') . ' ₽</span>
          </label>';
    }
}
