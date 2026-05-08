<?php

namespace sendInvoice;

/**
 * Selector represents a quantity-block for the order calculator.
 *
 * @package sendInvoice
 */
class Selector extends FormElement
{
    protected const CSS_FILE = '../styles/Selector.css';

    public function render(): string
    {
        return '
          <select id="quantity" name="quantity" required>
          </select>';
    }
}
