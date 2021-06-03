<?php
    use PHPMailer\PHPMailer\PHPMailer;
    session_start();

    if ($_SESSION['STATUS'] != 'faculty') {
    echo '<script>window.location.href="../index.php"</script>';
    } else if ($_SESSION['STATUS'] == 'student') {
        echo '<script>window.location.href="student/student_landing.php"</script>';
    } else if ($_SESSION['VERIFY_APPLICATION_FORM_STATUS'] != 'yes') {
        echo '<script>window.location.href="verify.php"</script>';
    } 

    $api = "http://icsdloa.online/cybersolution_backend/control/";
    $stud_id = $_SESSION['verify_student_id'];

    $ch = curl_init();
    $url = $api.'adviser/viewOneForm.php';

    $post_data = array("student_id" => $stud_id);
    $header = ['Content-type: Text/plain'];

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
   
    $output = curl_exec($ch);
    $decoded = json_decode($output);
    curl_close($ch);

    foreach ($decoded as $obj) {
        $firstname_form = $obj->firstname;
        $middlename_form = $obj->middlename;
        $lastname_form = $obj->lastname;
        $course_form = $obj->course;
        $section_form = $obj->section;
        $year_form = $obj->year;
        $gpa_form = $obj->gpa;
    }

    $ch = curl_init();
    $url = $api.'adviser/fetchGrade.php';

    $post_data = array ("student_id" => $stud_id);
    $header = ['Content-type: Text/plain'];

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

    $output = curl_exec($ch);
    $decoded1 = json_decode($output);

    curl_close($ch);

    $ch = curl_init();

    $url = $api.'adviser/fetchOneStudent.php';
    $post_data = array ("student_id" => $stud_id);
    $header = ['Content-Type: Text/plain'];

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    $output1 = curl_exec($ch);
    $student_decoded = json_decode($output1);

    foreach ($student_decoded as $obj1) {
        $student_email = $obj1->email;
        $student_name = $obj1->firstname;
    }

    if ($output1 === false) {
        echo "cURL Error: " . curl_error($ch);
    }
    curl_close($ch);

    if (isset($_POST['approve'])) {  

        $name = 'ICS-DLOA';
        $email = $student_email;
        $subject = 'Application Form Accepted';
        $body = '<h2>Hello '.$student_name.'</h2><br/> <strong>Your Application form has verified</strong> <br/> your application is now waiting to be approved by your Department head';
    
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
            $url = $api.'adviser/acceptForm.php';
            $post_data = array ("student_id" => $stud_id);
            $header = ['Content-type: Text/plain'];
    
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            $output = curl_exec($ch);
    
            if ($output === false) {
                echo 'cURL Error '. curl_error($ch);
            } else {
                curl_close($ch);
                $ch = curl_init();
                $url = $api.'adviser/acceptFormDefaults.php';
                $post_data = array ("student_id" => $stud_id);
                $header = ['Content-type: Text/plain'];
    
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
                curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
                $output = curl_exec($ch);
        
                if ($output === false) {
                    echo 'cURL Error '. curl_error($ch);
                } else {
                    curl_close($ch);
                    echo '<script>window.location.href="verify.php"</script>';
                }
            }
        } else {
            $status = "failed";
            $response = "Something is wrong: <br><br>" . $mail->ErrorInfo;
        }

        
    } else if (isset($_POST['decline'])) {
        $_SESSION['DECLINE_APPFORM_STATUS'] = 'yes';
        $_SESSION['DECLINE_FORM_STUDENT_ID'] = $stud_id;
        $_SESSION['DECLINE_FORM_STUDENT_EMAIL'] = $student_email;
        $_SESSION['DECLINE_FORM_STUDENT_NAME'] = $student_name;
        echo '<script>window.location.href="reason.php"</script>';

    } else if (isset($_POST['print'])) {
        echo '<script>window.open("print.php","_blank")</script>';

    } else if (isset($_POST['logout'])) {
        session_destroy();
        echo '<script>window.location.href="../index.php"</script>';
    } else if (isset($_POST['modal'])) {
        echo" <script>window.location.href='#modal'</script>";
        
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
    <title>ICS-DLOA | Form</title>
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
                <a href="gate.php">Registration</a>
                <a href="student.php">List of Students</a>
                <a href="verify.php">Verify DLOA Appform</a>
                <a href="facultyLanding.php">Role Select</a>
                
                <form action="studentForm.php" method="POST">

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
            
                <div class="student-verify-intro">
                    <h1>Verify</h1>
                    <span>Name: <?php echo $firstname_form.' '.$middlename_form.' '.$lastname_form; ?></span>
                    <span>Course: <?php echo $course_form; ?></span>
                    <span>Section: <?php echo $section_form; ?></span>
                    <span>Year: <?php echo $year_form; ?></span>
                    <span>GPA: <?php echo $gpa_form; ?></span>
                </div>    

                <table class="table_achievers">
                    <tr>
                        <th>Subject code</th>
                        <th>Subject name</th>
                        <th>grade</th>
                    </tr>
    

                    <?php  foreach ($decoded1 as $obj): ?>
                        <tr>
                            <td><?php echo $obj->subject_code; ?></td>
                            <td><?php echo $obj->subject_name; ?></td>
                            <td><?php echo $obj->grade; ?></td>
                        </tr>
                    <?php endforeach; ?>
                    
                </table>

                <div class="float_right">
                    <form action="studentForm.php" method="POST">

                        <input type="submit"  name="modal" value="Approve" class="modal-open">
                        <input type="submit" class="decline-btn" name="decline" id="decline" value="Decline">
                        <input type="submit" class ="blue-btn" name="print" id="print" value="Print">
                    </form>
                </div>

                    <div class="modal" id="modal">
                        <div class="modal__content">
                        <a href="#" class="modal__close">&times;</a>
                        <h2 class="modal__heading">Confirm</h2>
                            <span>Continue to approve student application form?</span>
                            <form action="studentForm.php" method="POST">
                                <input type="submit" class="blue-btn" value="Cancel">
                                <input type="submit" class="primary-btn" name="approve" value="Continue">
                            </form>
                        </div>
                    </div>

             </div>
        </div>
    </div>
    
    <script src="../assets/modal.js" type="text/javascript"></script>
</body>
</html>