<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMalier\Exception;



require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

$message=$_POST['htmlcontent'];
$Subject=$_POST['Subject'];
$sendTo=$_POST['SendTo'];


// Instantiation and passing [ICODE]true[/ICODE] enables exceptions
$mail = new PHPMailer(true);
try {
    //Server settings
    $mail->SMTPDebug = 0;                                       // Enable verbose debug output
    $mail->isSMTP();                                            // Set mailer to use SMTP
    $mail->Host       ='mail.expresshiping.com';  // Specify main and backup SMTP servers
    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
    $mail->Username   ='delivery@expresshiping.com';                     // SMTP username   
    $mail->Password   ='Epx34S09OO980!@#hkNik123&0LQqid';                               // SMTP password
    $mail->SMTPSecure ='tls';                                  // Enable TLS encryption, [ICODE]ssl[/ICODE] also accepted
    $mail->Port       =587;                                    // TCP port to connect to

    //Recipients
    $mail->setFrom("delivery@expresshiping.com", "Expresshiping & Logistics");
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

<div style="background: #eee;">
<div class="mailbody" style=" background: #fff; border-top: 10px solid #fa4612; border-bottom: 10px solid  #fa4612;"><div class="" style="background-image: url(https://www.expresscourierway.com/assets/img/slider/5.jpg); background-size: cover; background-position: center center; height: 300px"></div></div>

<div style="padding: 20px; font-size: 14pt; line-height: 30px">'.$message.'
<br>

<br>
</div>
</div>
<div class="mailfooter" align="center" style="color: white; background: #fa4612; padding: 20px;">
<address style="font-size: 12pt; padding: 20px">
Expresshiping & Logistics <br> Delaware,United States</address>
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
