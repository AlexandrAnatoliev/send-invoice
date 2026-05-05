<?php

namespace sendInvoice;

class Card
{
    protected const CSS = 'styles/Card.css';
    protected string $name;
    protected int $price;
    protected string $image;

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
                    data-price="' . $this->getPrice() . '"
                    data-name="' . htmlspecialchars($this->getName()) . '"
                    required>
            <img src="' . $this->getImage() . '" alt="' . $this->getName() . '">
            <span class="title">' . $this->getName() . '</span>
            <span class="price">' . number_format($this->getPrice(), 0, ',', ' ') . ' ₽</span>
          </label>';
    }
}
