<?php

namespace sendInvoice;

/**
 * Card represents a product card for the order calculator.
 *
 * Each card includes a name, price,
 * and an image path.
 *
 * @package sendInvoice
 */
abstract class Card extends FormElement
{
    protected int $price;
    protected string $image;

    public const CSS = 'styles/Card.css';

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
     * Create a new instance.
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

    public function getPrice(): int
    {
        return $this->price;
    }

    public function getImage(): string
    {
        return $this->image;
    }
}
