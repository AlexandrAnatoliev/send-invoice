<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

/**
 * Generate PDF from HTML invoice.
 *
 * This function configures Dompdf to handle Cyrillic characters
 * correctly, sets A4 format and margins, renders the PDF, and
 * returns its contents as string
 *
 * @param  string $html - the full HTML-docs of the invoice
 * @return string       - binary contents of the PDF-file
 */
function generatePDF(string $html): string
{
  $options = new Options();
  $options->set('isRemoteEnabled', true);
  $options->set('defaultFont', 'dejavu sans');
  $options->set('chroot', __DIR__);

  $dompdf = new Dompdf($options);
  $dompdf->loadHtml($html);
  $dompdf->setPaper('A4', 'portrait');
  $dompdf->render();

  return $dompdf->output();
}
