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
