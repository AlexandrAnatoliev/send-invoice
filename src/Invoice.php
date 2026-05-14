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
abstract class Invoice extends FormElement
{
    protected const CSS_FILE = '../styles/Invoice.css';
}
