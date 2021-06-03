<?php
    use PHPMailer\PHPMailer\PHPMailer;

    $name = 'ICS-DLOA';
    $email = 'spiderdudong112233@gmail.com';
    $subject = 'Approved';
    $body = "<strong>Your Registration form was approved</strong> <br/> you can now check and login to ICS-DLOA!";

    require_once "PHPMailer/PHPMailer.php";
    require_once "PHPMailer/SMTP.php";
    require_once "PHPMailer/Exception.php";

    $mail = new PHPMailer();

    //SMTP Settings
    $mail->isSMTP();
    $mail->Host = "smtp.gmail.com";
    $mail->SMTPAuth = true;
    $mail->Username = "icsdloa@gmail.com";
    $mail->Password = 'icsuser112233';
    $mail->Port = 465; //587
    $mail->SMTPSecure = "ssl"; //tls

    //Email Settings
    $mail->isHTML(true);
    $mail->setFrom($email, $name);
    $mail->addAddress($email);
    $mail->Subject = $subject;
    $mail->Body = $body;

    if ($mail->send()) {
        $status = "success";
        $response = "Email is sent!";
    } else {
        $status = "failed";
        $response = "Something is wrong: <br><br>" . $mail->ErrorInfo;
    }

?>
