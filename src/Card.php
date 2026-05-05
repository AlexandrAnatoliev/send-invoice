<?php

namespace sendInvoice;

class Card
{
    public const CSS = 'styles/Card.css';
    public string $name;
    public int $price;
    public string $image;

    public function __construct(
        string $name,
        int $price,
        string $image,
    ) {
        $this->name = $name;
        $this->price = $price;
        $this->image = $image;
    }

    public static function getCardCSS(): string
    {
        $cssContent = '';
        if (file_exists(self::CSS)) {
            $cssContent = file_get_contents(self::CSS);
        }
        return '<style>' . $cssContent . '</style>';
    }

    public function getCard(): string
    {
        return '
          <label class="card">
            <input type="radio" name="itemName"
                    value="' . $this->name . '"
                    data-price="' . $this->price . '"
                    data-name="' . htmlspecialchars($this->name) . '"
                    required>
            <img src="' . $this->image . '" alt="' . $this->name . '">
            <span class="title">' . $this->name . '</span>
            <span class="price">' . number_format($this->price, 0, ',', ' ') . ' ₽</span>
          </label>';
    }
}
