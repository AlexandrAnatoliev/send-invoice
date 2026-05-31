<?php

declare(strict_types=1);

namespace sendInvoice;

/**
 * Selector represents a quantity-block for the order calculator.
 *
 * @package sendInvoice
 */
class Selector extends FormElement
{
    protected const CSS_FILE = '../styles/Selector.css';
    private int $min;
    private int $max;
    private int $step;

    /**
     * Create a new instance.
     *
     * @param $name Selector name
     * @param $min  Selector min value
     * @param $max  Selector max value
     * @param $step Selector increment step
     */
    public function __construct(
        string $name,
        int $min,
        int $max,
        int $step,
    ) {
        parent::__construct($name);
        $this->min = $min;
        $this->max = $max;
        $this->step = $step;
    }

    public function getMin(): int
    {
        return $this->min;
    }

    public function getMax(): int
    {
        return $this->max;
    }

    public function getStep(): int
    {
        return $this->step;
    }

    /**
     * Render the Selector as an HTML selector containing a quantity-block selector.
     *
     * @return string
     */
    public function render(): string
    {
        $html = '<select id="quantity" name="quantity" required>';

        $value = $this->min;
        while ($value <= $this->max) {
            $html .= '
              <option value="' . $value . '">' . $value . ' шт.</option>';
            $value += $this->step;
        }

        $html .= '</select>';

        return $html;
    }
}
