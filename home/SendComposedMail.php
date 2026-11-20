<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMalier\Exception;


require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
$message=$_POST['htmlcontent'];
$Subject=$_POST['Subject'];
$sendTo=$_POST['SendTo'];
$Support="<a style='padding: 10px; text-decoration: none; color: white; background: #fa4612' href='https://tawk.to/chat/62d902bf37898912e95ee1b5/1g8fr5fuq'>Chat Live Support</a>";
// Instantiation and passing [ICODE]true[/ICODE] enables exceptions
$mail = new PHPMailer(true);

try {
   //Server settings
    $mail->SMTPDebug = 0;                                       // Enable verbose debug output
    $mail->isSMTP();                                            // Set mailer to use SMTP
    $mail->Host       = 'mail.expresscourierway.com';  // Specify main and backup SMTP servers
    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
    $mail->Username   = 'info@expresscourierway.com';                     // SMTP username
    $mail->Password   = 'ExpressCourier123!@#';                               // SMTP password
    $mail->SMTPSecure = 'tls';                                  // Enable TLS encryption, [ICODE]ssl[/ICODE] also accepted
    $mail->Port       = 26;                                    // TCP port to connect to

    //Recipients
    $mail->setFrom('info@expresscourierway.com', 'Expressway Courier Service');
    $mail->addAddress($sendTo);     // Add a recipient



    // Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = $Subject;
    $mail->Body    = '<html>
<head>
	<TITLE>Untitled-1</TITLE>
	<META http-equiv="Content-Type" Content="text/html; charset=utf-8">
<meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=0.5" />



</head>

<style type="text/css">

</style>
<body bgcolor="#FFFFFF">

<div style="padding: 20px; background: #eee;">
<div class="mailbody" style=" background: #fff; border-top: 10px solid #fa4612; border-bottom: 10px solid  #fa4612;"><div class="" style="background-image: url(https://www.expresscourierway.com/assets/img/slider/5.jpg); background-size: cover; background-position: center center; height: 300px"></div></div>

<div style="padding: 20px; font-size: 14pt; line-height: 30px">'.$message.'<br><br>For more information about this mail, chat with our Live Support Team by clicking the link below.<br><br><br>'.$Support.'<br><br> Thank you. <br><br> Expressway Courier Service.
<br>

<br>
</div>
</div>
<div class="mailfooter" align="center" style="color: white; background: #fa4612; padding: 20px;">
<address style="font-size: 12pt; padding: 20px">
Expressway Courier Service <br>  Louvre 33 Rue Du Uloho Avenue, Paris France</address>
<hr>
<p style="font-size: 12pt; padding: 20px">
To unsubscribe from this type of email, click <br><a href="#">UNSUBSCRIBE</a><br>
</p>

</div>
</div>


</body>
</html>';
$mail->send();

   

} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}




if($mail){
echo "Message Sent";
}
?>
