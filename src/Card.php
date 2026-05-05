<?php

namespace sendInvoice;

/**
 * Card represents a product card for the order calculator.
 *
 * Each card includes a name, price,
 * and an image path. It can render itself as an HTML radio-button label
 * and provides static CSS injection.
 *
 * @package sendInvoice
 */
class Card
{
    protected const CSS = 'styles/Card.css';
    protected string $name;
    protected int $price;
    protected string $image;

    /**
     * Create a new Card instance.
     *
     * @param $name  Product name
     * @param $price Product price
     * @param $image Image path (typically inside img/)
     */
    public function __construct(
        string $name,
        int $price,
        string $image,
    ) {
        $this->name = $name;
        $this->price = $price;
        $this->image = $image;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function getImage(): string
    {
        return $this->image;
    }

    /**
     * Return a <style> tag with the contents of the card CSS file.
     *
     * If the file does not exist, an empty <style> tag is returned.
     *
     * @return HTML style tag
     */
    public static function getCardCSS(): string
    {
        $cssContent = '';
        if (file_exists(self::CSS)) {
            $cssContent = file_get_contents(self::CSS);
        }
        return '<style>' . $cssContent . '</style>';
    }

    /**
     * Render the card as an HTML label containing a radio input.
     *
     * The radio element carries data-price (raw integer) and data-name
     * (HTML-escaped) attributes for JavaScript consumption. The visible
     * price is formatted with a thousands separator and the Ruble sign.
     *
     * @return HTML markup of the card
     */
    public function getCard(): string
    {
        return '
          <label class="card">
            <input type="radio" name="itemName"
                    value="' . $this->getName() . '"
                    data-price="' . $this->getPrice() . '"
                    data-name="' . htmlspecialchars($this->getName()) . '"
                    required>
            <img src="' . $this->getImage() . '" alt="' . $this->getName() . '">
            <span class="title">' . $this->getName() . '</span>
            <span class="price">' . number_format($this->getPrice(), 0, ',', ' ') . ' ₽</span>
          </label>';
    }
}
