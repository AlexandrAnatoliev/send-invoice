<?php

namespace sendInvoice;

class Card
{
    public string $name;
    public string $css;
    public int $price;
    public string $image;

    public function __construct(
        string $name,
        string $css,
        int $price,
        string $image,
    ) {
        $this->name = $name;
        $this->css = $css;
        $this->price = $price;
        $this->image = $image;
    }

    public function getCardCSS(): string
    {
        $cssContent = '';
        if (file_exists($this->css)) {
            $cssContent = file_get_contents($this->css);
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
