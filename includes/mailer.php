<?php

declare(strict_types=1);

require_once __DIR__ . '/helpers.php';

function mailer_send(string $toEmail, string $toName, string $subject, string $htmlBody, string $replyToEmail = '', string $replyToName = ''): array
{
    $phpMailerDir = __DIR__ . '/../vendor/phpmailer/phpmailer/src';
    $ph = $phpMailerDir . '/PHPMailer.php';
    $ex = $phpMailerDir . '/Exception.php';
    $st = $phpMailerDir . '/SMTP.php';

    if (!file_exists($ph) || !file_exists($ex) || !file_exists($st)) {
        return ['ok' => false, 'error' => 'PHPMailer not found in vendor folder.'];
    }

    require_once $ex;
    require_once $ph;
    require_once $st;

    $smtpHost = (string)getenv('SMTP_HOST');
    $smtpPort = (int)(getenv('SMTP_PORT') ?: 587);
    $smtpUser = (string)getenv('SMTP_USER');
    $smtpPass = (string)getenv('SMTP_PASS');
    $smtpSecure = (string)(getenv('SMTP_SECURE') ?: 'tls');

    $fromEmail = (string)(getenv('MAIL_FROM_EMAIL') ?: 'no-reply@localhost');
    $fromName = (string)(getenv('MAIL_FROM_NAME') ?: APP_NAME);

    try {
        $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
        $mail->CharSet = 'UTF-8';
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $htmlBody;

        if ($smtpHost !== '' && $smtpUser !== '') {
            $mail->isSMTP();
            $mail->Host = $smtpHost;
            $mail->SMTPAuth = true;
            $mail->Username = $smtpUser;
            $mail->Password = $smtpPass;
            $mail->Port = $smtpPort;
            if ($smtpSecure !== '') {
                $mail->SMTPSecure = $smtpSecure;
            }
        } else {
            $mail->isMail();
        }

        $mail->setFrom($fromEmail, $fromName);
        if ($replyToEmail !== '') {
            $mail->addReplyTo($replyToEmail, $replyToName !== '' ? $replyToName : $replyToEmail);
        }
        $mail->addAddress($toEmail, $toName !== '' ? $toName : $toEmail);

        $mail->send();
        return ['ok' => true];
    } catch (Throwable $e) {
        return ['ok' => false, 'error' => $e->getMessage()];
    }
}
