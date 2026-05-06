<?php

namespace sendInvoice;

/**
 * FormElement represents an element for the order display.
 *
 * Each FormElement includes a name
 *
 * @package sendInvoice
 */
abstract class FormElement
{
    protected string $name;

    /**
     * Create a new instance.
     *
     * @param $name  Product name
     */
    public function __construct(
        string $name,
    ) {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
