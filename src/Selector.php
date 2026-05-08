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
    protected int $min;
    protected int $max;
    protected int $step;

    /**
     * Create a new instance.
     *
     * @param $name Selector name
     * @param $min  Selector min value
     * @param $max  Selector max value
     * @param $step Selector oscillation step
     */
    public function __construct(
        string $name,
        int $min,
        int $max,
        int $step,
    ) {
        parent::__construct($name);
        $this->min = $min;
        $this->min = $max;
        $this->min = $step;
    }

    public function render(): string
    {
        return '
          <select id="quantity" name="quantity" required>
          </select>';
    }
}
