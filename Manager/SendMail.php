<?php
include_once('SiteConfig.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once '../PHPMailer/src/Exception.php';
require_once '../PHPMailer/src/PHPMailer.php';
require_once '../PHPMailer/src/SMTP.php';

include_once('../Connect.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method not allowed');
}

$sendTo = filter_var($_POST['SendTo'] ?? '', FILTER_VALIDATE_EMAIL);
$subject = trim((string)($_POST['Subject'] ?? ''));
$message = (string)($_POST['htmlcontent'] ?? '');

if (!$sendTo || $subject === '' || trim(strip_tags($message)) === '') {
    http_response_code(422);
    exit('Please provide a valid recipient, subject and message.');
}

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->SMTPDebug = 0;
    $mail->CharSet = 'UTF-8';
    $mail->Encoding = 'base64';
    $mail->Host = $SMTPhost;
    $mail->SMTPAuth = true;
    $mail->Username = $SiteEmail;
    // Keep SMTP credentials in SiteConfig.php (ideally loaded from environment variables).
    $mail->Password = 'Epx34S09OO980!@#hkNik123&0LQqi';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;
    $mail->Timeout = 30;

    $mail->setFrom($SiteEmail, $MailerName);
    $mail->addAddress($sendTo);
    if (filter_var($SiteEmail, FILTER_VALIDATE_EMAIL) && strtolower($sendTo) !== strtolower($SiteEmail)) {
        $mail->addAddress($SiteEmail);
    }

    $safeSubject = htmlspecialchars($subject, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    $brandName = htmlspecialchars($SiteName ?? 'Expresshiping & Logistics', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    $year = htmlspecialchars((string)($Establishment_Year ?? date('Y')), ENT_QUOTES, 'UTF-8');

    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body = '<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <meta name="x-apple-disable-message-reformatting">
  <title>' . $safeSubject . '</title>
</head>
<body style="margin:0;padding:0;background-color:#f2f5f7;font-family:Arial,Helvetica,sans-serif;color:#243342;">
  <div style="display:none;max-height:0;overflow:hidden;opacity:0;color:transparent;">' . $safeSubject . ' — an update from ' . $brandName . '.</div>
  <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color:#f2f5f7;">
    <tr><td align="center" style="padding:28px 12px;">
      <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="600" style="width:100%;max-width:600px;background:#ffffff;border-radius:12px;overflow:hidden;">
        <tr>
          <td style="background:#102b3f;padding:22px 30px;border-bottom:4px solid #0b8a78;">
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
              <tr>
                <td style="font-size:20px;line-height:26px;font-weight:700;letter-spacing:.2px;color:#ffffff;">' . $brandName . '</td>
                <td align="right" style="font-size:10px;line-height:16px;font-weight:700;letter-spacing:1.5px;color:#9edbd1;">FREIGHT • LOGISTICS • DELIVERY</td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td style="padding:0;background:#173d54;">
            <img src="https://images.unsplash.com/photo-1494412519320-aa613dfb7738?auto=format&amp;fit=crop&amp;w=1200&amp;q=85" width="600" alt="Cargo containers aboard a freight vessel" style="display:block;width:100%;max-width:600px;height:auto;border:0;">
          </td>
        </tr>
        <tr>
          <td style="padding:30px 34px 10px;">
            <div style="font-size:11px;line-height:16px;font-weight:700;letter-spacing:1.8px;text-transform:uppercase;color:#0b8a78;">OFFICIAL COMMUNICATION</div>
            <h1 style="margin:10px 0 0;font-size:25px;line-height:33px;font-weight:700;color:#102b3f;">' . $safeSubject . '</h1>
          </td>
        </tr>
        <tr>
          <td style="padding:14px 34px 34px;font-size:15px;line-height:1.8;color:#465563;overflow-wrap:break-word;">
            ' . $message . '
          </td>
        </tr>
        <tr>
          <td style="padding:22px 34px;background:#f7fafb;border-top:1px solid #e7edf0;">
            <p style="margin:0 0 8px;font-size:13px;line-height:20px;font-weight:700;color:#102b3f;">Moving cargo. Connecting destinations.</p>
            <p style="margin:0;font-size:12px;line-height:19px;color:#71808c;">Thank you for choosing ' . $brandName . '. For shipment assistance, please reply to this email or contact our support team.</p>
          </td>
        </tr>
        <tr>
          <td align="center" style="padding:20px 24px;background:#102b3f;">
            <p style="margin:0 0 7px;font-size:11px;line-height:18px;color:#d5e1e7;">&copy; ' . $year . ' ' . $brandName . '. All rights reserved.</p>
            <p style="margin:0;font-size:10px;line-height:16px;color:#9fb2bf;">This email was sent to you by ' . $brandName . '. If you received it unexpectedly, please contact us.</p>
          </td>
        </tr>
      </table>
      <div style="padding:14px 10px 0;font-size:10px;line-height:16px;color:#8997a1;">' . $brandName . ' · Reliable freight and logistics solutions</div>
    </td></tr>
  </table>
</body>
</html>';

    $mail->AltBody = trim(preg_replace('/\s+/', ' ', strip_tags(str_replace(['<br>', '<br/>', '<br />', '</p>', '</div>'], "\n", $message))));
    $mail->send();
    echo '1';
} catch (Exception $e) {
    error_log('Manager SendMail error: ' . $mail->ErrorInfo);
    http_response_code(500);
    echo '0';
}
?>