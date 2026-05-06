<?php

namespace sendInvoice;

/**
 * FormElement represents a element for the order display.
 *
 * Each FormElement includes a name
 *
 * @package sendInvoice
 */
abstract class FormElement
{
    protected string $name;

    public function getName(): string
    {
        return $this->name;
    }
}
