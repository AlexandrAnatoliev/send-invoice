<?php

declare(strict_types=1);

namespace sendInvoice;

/**
 * Base class for order form UI elements that render HTML.
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
   * @param $name  Element name
   */
  public function __construct(
    string $name,
  ) {
    $this->name = $name;
  }

  abstract public function render(): string;

  /**
   * Return a <style> tag with the contents of the CSS file.
   *
   * If the file does not exist, an empty <style> tag is returned.
   *
   * @return string
   */
  public static function getCSS(): string
  {
    $cssContent = '';
    $relativePath = static::CSS_FILE;
    if ($relativePath !== '') {
      $cssFilePath = __DIR__ . '/' . $relativePath;
      if (file_exists($cssFilePath) && is_file($cssFilePath)) {
        $cssContent = file_get_contents($cssFilePath);
      }
    }
    return '<style>' . $cssContent . '</style>';
  }

  /**
   * Return instance name.
   *
   * @return string
   */
  public function getName(): string
  {
    return $this->name;
  }
}
