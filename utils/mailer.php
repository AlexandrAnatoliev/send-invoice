<?php

declare(strict_types=1);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use sendInvoice\Config;

/**
 * Sends an HTML email to the recipient via the configured SMTP server.
 *
 * @param string $toEmail     Recipient email address
 * @param string $toName      Recipient display name
 * @param string $subject     Email subject
 * @param string $htmlBody    HTML message body
 * @param Config $config      Application configuration
 * @param string $pdfContent  PDF content
 * @param string $pdfFilename PDF file name
 * @return bool True on success, false on failure
 */
function sendEmail(
  string $toEmail,
  string $toName,
  string $subject,
  string $htmlBody,
  Config $config,
  string $pdfContent = '',
  string $pdfFilename = 'invoice.pdf',
): bool {
  $mail   = new PHPMailer(true);

  try {
    $mail->SMTPDebug    = SMTP::DEBUG_OFF;
    $mail->isSMTP();
    $mail->Host         = $config->get('HOST');
    $mail->SMTPAuth     = true;
    $mail->Username     = $config->get('USERNAME');
    $mail->Password     = $config->get('PASSWORD');
    $mail->SMTPSecure   = $config->get('ENCRYPTION');
    $mail->Port         = $config->get('PORT');
    $mail->CharSet      = $config->get('CHARSET');
    $mail->SMTPOptions  = [
      'ssl' => [
        'verify_peer'       => false,
        'verify_peer_name'  => false,
        'allow_self_signed' => true,
      ],
    ];

    $mail->setFrom($config->get('USERNAME'), 'Калькулятор заказа');
    $mail->addAddress($toEmail, $toName);
    $mail->addReplyTo($config->get('USERNAME'), 'Поддержка');
    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body    = $htmlBody;
    $mail->AltBody = strip_tags($htmlBody);

//     Attach PDF when $pdfContent is provided (see commented parameters above)
    if (!empty($pdfContent)) {
      $mail->addStringAttachment(
        $pdfContent,
        $pdfFilename,
        'base64',
        'application/pdf'
      );
    }

    $mail->send();
    return true;
  } catch (Exception $e) {
    echo "<div style='color:red; padding:10px; border:1px solid red;'>";
    echo "<strong>Ошибка отправки:</strong><br>";
    echo $mail->ErrorInfo;
    echo "</div>";
    return false;
  }
}
