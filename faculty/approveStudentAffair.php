<?php
    session_start();

    if ($_SESSION['STATUS'] != 'faculty') {
        echo '<script>window.location.href="../index.php"</script>';
    } else if ($_SESSION['STATUS'] == 'student') {
        echo '<script>window.location.href="student/student_landing.php"</script>';
    } else if ($_SESSION['STUDENT_COORD_STATUS'] != 'yes') {
        echo '<script>window.location.href="facultyLanding.php"</script>';
    }

    $api = "http://icsdloa.online/cybersolution_backend/control/";
    
    $ch = curl_init();
    $url = $api.'committee/viewStudentAffair.php';
    $counter = 0;

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $resp = curl_exec($ch);

    if ($e = curl_error($ch)) {
        echo $e;
    } else {
        $decoded = json_decode($resp);
    } curl_close($ch);
    
    if (isset($_POST['approve'])) {
        $student_id = $_POST['student_id'];
        $ch = curl_init();

        $url = $api.'committee/approveStudentAffair.php';
        $post_data = array ("student_id" => $student_id);
        $header = ['Content-Type: Text/plain'];

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        $output = curl_exec($ch);

        if ($output === false) {
            echo "cURL Error: " . curl_error($ch);
        }
        curl_close($ch);
        echo '<script>window.location.href="approveStudentAffair.php"</script>';
        
    } else if (isset($_POST['decline'])) {
        $stud_id = $_POST['student_id'];

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
        $_SESSION['COMMITTEE_STUDENT_ID_DECLINE'] = $_POST['student_id'];
        $_SESSION['COMMITTEE_STUDENT_EMAIL_DECLINE'] = $student_email;
        $_SESSION['COMMITTEE_DECLINE_STATUS'] ='yes';

        echo '<script>window.location.href="reason_committee.php"</script>';
    }

    if (isset($_POST['logout'])) {
        session_destroy();
        echo "<script>window.location.href='../index.php'</script>";

    } else if (isset($_POST['modal'])) {
        $_SESSION['STUDENT_AFFAIR_STUDENT_ID'] = $_POST['student_id'];
        echo" <script>window.location.href='#modal'</script>"; 
    } 

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/modal2.css">
    <link rel="stylesheet" href="../assets/modal.css">
    <link rel="stylesheet" href="../assets/style.css">
    <title>ICS-DLOA | Forms</title>
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
                <a href="facultyLanding.php">Role Select</a>
                
                <form action="approveStudentAffair.php" method="POST">

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
                <h1>Student Affair Approval Form</h1>
                <table class="table_achievers">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Student id</th>
                        <th>Section</th>
                        <th>Date</th>
                        <th>Course</th>
                        <th>GPA</th>
                        <th>Actions</th>
                    </tr>
    

                    <?php if ($decoded != 'no students found') { ?>
                        <?php foreach ($decoded as $obj): ?>
                            <tr class="student-table">
                            <?php $counter+=1;?>
                            <td><?php echo $counter; ?></td>
                            <td><?php echo $obj->lastname.', '.$obj->firstname.' '.$obj->middlename; ?></td>
                            <td><?php echo $obj->student_id; ?></td>
                            <td><?php echo $obj->section; ?></td>
                            <td><?php echo $obj->date; ?></td>
                            <td><?php echo $obj->course; ?></td>
                            <td><?php echo $obj->gpa; ?></td>
                                <td>
                                    <form action="approveStudentAffair.php" method="POST">
                                        <input type="hidden" name="student_id" id="student_id" value="<?php echo $obj->student_id; ?>">
                                        <input type="submit" name="modal" value="Approve" class="modal-open">
                                        <input type="submit" name="decline" id="decline" class="decline-btn" value="Decline">
                                    </form>
                                    
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php } ?>

                        <div class="modal" id="modal">
                            <div class="modal__content">
                            <a href="#" class="modal__close">&times;</a>
                            <h2 class="modal__heading">Confirm</h2>
                                <span>Continue to approve application form?</span>
                                <form action="approveStudentAffair.php" method="POST">
                                    <input type="hidden" name="student_id" value="<?php echo $_SESSION['STUDENT_AFFAIR_STUDENT_ID']?>">
                                    <input type="submit" class="blue-btn" name="close" value="Cancel">
                                    <input type="submit" class="primary-btn" name="approve" value="Continue">
                                </form>
                            </div>
                        </div>
                    
                </table>
             </div>
        </div>
    </div>
    

    <script src="../assets/modal.js" type="text/javascript"></script>

</body>
</html>