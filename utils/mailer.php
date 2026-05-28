<?php

declare(strict_types=1);

require_once __DIR__ . '../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use sendInvoice\Config;

/**
 * Отправляет HTML-письмо получателю через настроенный SMTP-сервер
 * с прикреплённым PDF-файлом счёта.
 *
 * @param string $toEmail      - Email получателя
 * @param string $toName       - Имя получателя
 * @param string $subject      - Тема письма
 * @param string $htmlBody     - HTML-содержимое письма
//  * @param string $pdfContent   - Бинарное содержимое PDF (опционально)
 * @param string $pdfFilename  - Имя PDF-файла (опционально)
 * @param Config $config         Settings config
 * @return true|false
 */
function sendEmail(
  string $toEmail,
  string $toName,
  string $subject,
  string $htmlBody,
  //     string $pdfContent = '',
//   string $pdfFilename = 'invoice.pdf',
  Config $config
): bool {
  $mail   = new PHPMailer(true);

  try {
    $mail->SMTPDebug    = SMTP::DEBUG_OFF;
    $mail->isSMTP();
    $mail->Host         = $config->get('MAILER_HOST');
    $mail->SMTPAuth     = true;
    $mail->Username     = $config->get('MAILER_USERNAME');
    $mail->Password     = $config->get('MAILER_PASSWORD');
    $mail->SMTPSecure   = $config->get('MAILER_ENCRYPTION');
    $mail->Port         = $config->get('MAILER_PORT');
    $mail->CharSet      = $config->get('MAILER_CHARSET');
    $mail->SMTPOptions  = [
      'ssl' => [
        'verify_peer'       => false,
        'verify_peer_name'  => false,
        'allow_self_signed' => true,
      ],
    ];

    $mail->setFrom($config->get('MAILER_USERNAME'), 'Калькулятор заказа');
    $mail->addAddress($toEmail, $toName);
    $mail->addReplyTo($config->get('MAILER_USERNAME'), 'Поддержка');
    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body    = $htmlBody;
    $mail->AltBody = strip_tags($htmlBody);

    // Прикрепляем PDF, если он передан
//     if (!empty($pdfContent)) {
//       $mail->addStringAttachment(
//         $pdfContent,
//         $pdfFilename,
//         'base64',
//         'application/pdf'
//       );
   // }

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
