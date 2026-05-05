<?php

namespace sendInvoice;

class Card
{
    protected const CSS = 'styles/Card.css';
    protected string $name;
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

    public function getName(): string
    {
        return $this->name;
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
                    value="' . $this->getName() . '"
                    data-price="' . $this->price . '"
                    data-name="' . htmlspecialchars($this->getName()) . '"
                    required>
            <img src="' . $this->image . '" alt="' . $this->getName() . '">
            <span class="title">' . $this->getName() . '</span>
            <span class="price">' . number_format($this->price, 0, ',', ' ') . ' ₽</span>
          </label>';
    }
}
