<?php
    use PHPMailer\PHPMailer\PHPMailer;
    session_start();

    if ($_SESSION['STATUS'] != 'faculty') {
        echo '<script>window.location.href="../index.php"</script>';
    } else if ($_SESSION['STATUS'] == 'student') {
        echo '<script>window.location.href="student/student_landing.php"</script>';
    } else if ($_SESSION['ADVISER_STATUS'] != 'yes') {
        echo '<script>window.location.href="facultyLanding.php"</script>';
    } 
    
    $api = "http://icsdloa.online/cybersolution_backend/control/";

    $ch = curl_init();
    $url = $api.'admin/fetchCurriculum.php';
    $counter = 0;

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $resp = curl_exec($ch);

    if ($e = curl_error($ch)) {
        echo $e;
    } else {
        $curriculum_decoded = json_decode($resp);
    } curl_close($ch);


    $adviser_name = $_SESSION['USERNAME'];
    $ch = curl_init();
    $url = $api.'adviser/fetchGate.php';

    $post_data = array ("adviser_name" => $adviser_name);
    $header = ['Content-Type: Text/plain'];

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    $output = curl_exec($ch);
    $decoded = json_decode($output);

    if ($output === false) {
        echo "cURL Error: " . curl_error($ch);
    } curl_close($ch);

    if (isset($_POST['accept'])) {
        $student_id = $_POST['stud_id'];
        $curriculum = $_POST['curriculum_name'];

        $ch = curl_init();

        $url = $api.'adviser/fetchOneGate.php';
        $post_data = array ("student_id" => $student_id);
        $header = ['Content-Type: Text/plain'];

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        $output1 = curl_exec($ch);
        $decoded1 = json_decode($output1);

        foreach ($decoded1 as $obj1) {
            $student_email = $obj1->email;
            $student_name = $obj1->firstname;
        }

        if ($output1 === false) {
            echo "cURL Error: " . curl_error($ch);
        }
        curl_close($ch);  

        $name = 'ICS-DLOA';
        $email = $student_email;
        $subject = 'Registration Verified';
        $body = '<h2>Hello '.$student_name.'</h2><br/> <strong>Your Registration Has beed verified</strong> <br/> you can now login to ICS-DLOA';
    
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
            
            $ch = curl_init();

            $url = $api.'adviser/acceptGate.php';
            $post_data = array (
                "student_id" => $student_id,
                "curriculum" => $curriculum
            );
            $header = ['Content-Type: Text/plain'];
    
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            $output = curl_exec($ch);  
    
            if ($output === false) {
                echo "cURL Error: " . curl_error($ch);
            } 
    
            curl_close($ch);

            echo '<script>window.location.href="gate.php"</script>';
        } else {
            $status = "failed";
            $response = "Something is wrong: <br><br>" . $mail->ErrorInfo;
        }


    } else if (isset($_POST['decline'])) {
        $student_id = $_POST['student_id'];
        $ch = curl_init();

        $url = $api.'adviser/fetchOneGate.php';
        $post_data = array ("student_id" => $student_id);
        $header = ['Content-Type: Text/plain'];

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        $output1 = curl_exec($ch);
        $decoded1 = json_decode($output1);

        foreach ($decoded1 as $obj1) {
            $student_email = $obj1->email;
            $student_name = $obj1->firstname;
        }

        if ($output1 === false) {
            echo "cURL Error: " . curl_error($ch);
        }
        curl_close($ch);  

        $_SESSION['GATE_STUDENT_ID_DECLINE'] = $student_id;
        $_SESSION['GATE_STUDENT_EMAIL_DECLINE'] = $student_email;

        echo "<script>window.location.href='reason_gate.php'</script>";
        // echo '<script>window.location.href="gate.php"</script>';
    } if (isset($_POST['modal'])) {
        $_SESSION['APPROVE_STUDENT_ID'] = $_POST['student_id'];
        echo" <script>window.location.href='#modal'</script>";
        
    } if (isset($_POST['logout'])) {
        session_destroy();
        echo "<script>window.location.href='../index.php'</script>";
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=0.1">
    <link rel="stylesheet" href="../assets/modal2.css">
    <link rel="stylesheet" href="../assets/modal.css">
    <link rel="stylesheet" href="../assets/style.css">
    <title>ICS-DLOA | Gate</title>
</head>
<body>

    <div class="faculty_container">

        <div class="side_panel">
            
            <div class="ics_dloa">
                <img class="ics_logo" src="../assets/images/icslogo.png" alt="ics_logo">
                <div>
                    <span><strong>Western Mindanao State University</strong></span>
                    <h4>ICS-DLOA</h4>
                    <span>Faculty Member</span>
                </div>
            </div>

            <div class="name_container">
                <span>Welcome</span>
                <p><?php echo $_SESSION['USERNAME'];?></p>
            </div>

            <div class="navigation">
                <span>Please select an option:</span>
                <a href="gate.php">Student Registration</a>
                <a href="student.php">List of Students</a>
                <a href="verify.php">Verify DLOA Appform</a>
                <a href="facultyLanding.php">Back to Role Select</a>
                
                <form action="gate.php" method="POST">

                    <a onclick="showModal()">Logout</a>
                    <div id="alertBox">
                        <div id="box">
                            <div class="heading">
                                Confirm
                            </div>
                            <div class="content">
                                <p>Are you sure you wish to logout?</p>
                                <div id="button_container">
                                    <input type="submit" name="logout" id="logout" value="Yes" onclick="hideAlert()">
                                    <button id="cancel" onclick="hideAlert()">cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

            </div>
        
        </div>

        <div class="main_screen">

            <div class="table-container">
                <h1>Students Waiting for Approval</h1>
                <table class="table_achievers">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Student id</th>
                        <th>Course</th>
                        <th>Action</th>
                    </tr>
    

                    <?php if ($decoded != null) { ?>
                        <?php foreach ($decoded as $obj): ?>
                            <tr>
                                <?php $counter+=1;?>
                                <td><?php echo $counter;?></td>
                                <td><?php echo $obj->lastname.', '.$obj->firstname.' '.$obj->middlename;?></td>
                                <td><?php echo $obj->student_id;?></td>
                                <td><?php echo $obj->course;?></td>

                                <td>
                                    <form action="gate.php" method="POST">
                                            <input type="hidden" name="student_id" value="<?php echo $obj->student_id?>">
                                            
                                            <input type="submit" name="modal" value="Approve" class="modal-open">
                                            <input type="submit" name="decline" value="Decline" class="decline-btn">
                                    </form>
                                </td>

                            </tr>
                        <?php endforeach; ?>
                    <?php } ?>


                    <div class="modal" id="modal">
                        <div class="modal__content">
                        <a href="#" class="modal__close">&times;</a>
                        <h2 class="modal__heading">Confirm</h2>
                            <span>Continue to approve student registration?</span>
                            <form action="gate.php" method="POST">
                                <input type="hidden" name="stud_id" value="<?php echo $_SESSION['APPROVE_STUDENT_ID']?>">

                                <select name="curriculum_name" required>
                                    <option value="" disabled selected>Choose Curriculum</option>
                                    <?php foreach ($curriculum_decoded as $curriculum_obj) :?>
                                        <option value="<?php echo $curriculum_obj->curriculum_name?>"><?php echo $curriculum_obj->curriculum_name?></option>
                                    <?php endforeach; ?>
                                </select>
                                
                            
                                    <input type="submit" class="primary-btn" name="accept" value="Continue">
                                
                            </form>
                            <input type="submit" class="blue-btn" name="cancel_modal" onclick="history.back();" value="Cancel">
                        </div>
                    </div>

                    
                </table>
             </div>
        </div>
    </div>
    
    <script src="../assets/modal.js" type="text/javascript"></script>
</body>
</html>