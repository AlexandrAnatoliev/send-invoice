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
    protected const CSS_FILE = '';

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

    abstract public function render(): string;

    /**
     * Return a <style> tag with the contents of the card CSS file.
     *
     * If the file does not exist, an empty <style> tag is returned.
     *
     * @return HTML style tag
     */
    public static function getCSS(): string
    {
        $cssContent = '';
        if (file_exists(static::CSS_FILE)) {
            $cssContent = file_get_contents(static::CSS_FILE);
        }
        return '<style>' . $cssContent . '</style>';
    }

    public function getName(): string
    {
        return $this->name;
    }
}
