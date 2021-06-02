<?php
    use PHPMailer\PHPMailer\PHPMailer;
    session_start();
    $api = "http://icsdloa.online/cybersolution_backend/control/";

    if (isset($_POST['name']) && isset($_POST['email'])) {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $subject = $_POST['subject'];
        $stud_id = $_POST['student_id'];
        $body = $_POST['body'];

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
            $ch = curl_init();

            $url = $api.'admin/rejectForms.php';
            $post_data = array ("student_id" => $stud_id);
            $header = ['Content-Type: Text/plain'];
    
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            $output = curl_exec($ch);

            unset($_SESSION['COMMITTEE_STUDENT_EMAIL_DECLINE']);
            unset($_SESSION['COMMITTEE_STUDENT_ID_DECLINE']);
            $status = "success";
            $response = "Email is sent!";

        } else {
            $status = "failed";
            $response = "Something is wrong: <br><br>" . $mail->ErrorInfo;
        }

        exit(json_encode(array("status" => $status, "response" => $response)));
    }
?>
