<?php

declare(strict_types=1);

namespace sendInvoice;

/**
 * Item represents a product card for the order calculator.
 *
 * Each item includes a name, price,
 * and an image path. It can render itself as an HTML radio-button label
 *
 * @package sendInvoice
 */
class Item extends Card
{
    /**
     * Render the item as an HTML label containing a radio input.
     *
     * The radio element carries data-price (raw integer) and data-name
     * (HTML-escaped) attributes for JavaScript consumption. The visible
     * price is formatted with a thousands separator and the Ruble sign.
     *
     * @return HTML markup of the item
     */
    public function render(): string
    {
        return '
          <label class="card">
            <input type="radio" name="itemName"
                    value="' . htmlspecialchars($this->getName()) . '"
                    data-price="' . $this->getPrice() . '"
                    data-name="' . htmlspecialchars($this->getName()) . '"
                    required>
            <img src="' . htmlspecialchars($this->getImage())
              . '" alt="' . htmlspecialchars($this->getName()) . '">
            <span class="title">' . htmlspecialchars($this->getName()) . '</span>
            <span class="price">' . number_format($this->getPrice(), 0, ',', ' ') . ' ₽</span>
          </label>';
    }
}
