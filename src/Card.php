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

    protected const CSS_FILE = '../styles/Card.css';

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
        parent::__construct($name);
        $this->price = $price;
        $this->image = $image;
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
        return $this->price;
    }

    public function getImage(): string
    {
        return $this->image;
    }
}
