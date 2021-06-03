<?php
    session_start();

    if ($_SESSION['STATUS'] != 'student') {
        echo '<script>window.location.href="../index.php"</script>';
    } else if ($_SESSION['STATUS'] == 'faculty') {
        echo '<script>window.location.href="faculty/facultyLanding.php"</script>';
    }
    
    $api = "http://icsdloa.online/cybersolution_backend/control/";
    $counter = 0;
    $user_id = $_SESSION['STUDENT_ID'];

    $ch = curl_init();
    $url = $api."student/checkWaitingForms.php";
    $post_data = array ("student_id" => $user_id);
    $header = ['Content-type: Text/plain'];

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    $output = curl_exec($ch);
    $checkWaitingForms = json_decode($output);
    

    $ch = curl_init();
    $url = $api."student/checkCurrentAchievers.php";
    $post_data = array ("student_id" => $user_id);
    $header = ['Content-type: Text/plain'];

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    $output = curl_exec($ch);
    $checkCurrentAchievers = json_decode($output);


    $ch = curl_init();
    $url = $api."student/checkApprovedForms.php";
    $post_data = array ("student_id" => $user_id);
    $header = ['Content-type: Text/plain'];

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    $output = curl_exec($ch);
    $checkApprovedForms = json_decode($output);


    if ($checkWaitingForms != 'form does not exist') {
        $style = 'display : none;';
    } else if ($checkCurrentAchievers != 'form does not exist') {
        $style = 'display : none;';
    } else if ($checkApprovedForms != 'form does not exist') {
        $style = 'display : none;';
    } else {
        $style = 'display : block; display: flex; align-items: center;';
    }

    $ch = curl_init();
    $url = $api.'student/fetchStudentHistory.php';
    
    $post_data = array ("student_id" => $user_id);
    $header = ['Content-type: Text/plain'];

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    $output = curl_exec($ch);
    $decoded = json_decode($output);

    if (isset($_POST['view'])) {
        $_SESSION['ARCHIVE_YEAR'] = $_POST['year'];
        $_SESSION['ARCHIVE_SEMESTER'] = $_POST['semester'];
        $_SESSION['ARCHIVE_SCHOOL_YEAR'] = $_POST['school_year'];
        echo "<script>window.location.href='history_view.php'</script>";
    }

    if (isset($_POST['logout'])) {
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
    <link rel="stylesheet" href="../assets/modal.css">
    <link rel="stylesheet" href="../assets/style.css">
    
    <title>ICS-DLOA | History</title>
</head>
<body>
    <div class="student_main_container">

        <div class="side_panel">

            <div class="ics_dloa">
                <img class="ics_logo" src="../assets/images/icslogo.png" alt="ics_logo">
                <div>
                    <span><strong>Western Mindanao State University</strong></span>
                    <h4>ICS-DLOA</h4>
                    <span>Student</span>
                </div>
            </div>

            <div class="name_container">
                <span>Welcome</span>
                <p><?php echo $_SESSION['FIRSTNAME_STUDENT'].' '.$_SESSION['MIDDLENAME_STUDENT'].' '.$_SESSION['LASTNAME_STUDENT'] ?></p>
            </div>

            <div class="navigation">
                <a href="studentApply.php" style='<?php echo $style?>'>Apply</a>
                <a href="change_password.php">Change Password</a>
                <a href="application_history.php">View Past Records</a>
                
                <form action="application_history.php" method="POST">

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

            <div class="back_container">
                <a href="student_landing.php">Home</a>
            </div>

            <div class="table-container">
                <h1>Past Applications</h1>
                <table class="table_achievers">
                    <tr>
                        <th>Section</th>
                        <th>Course</th>
                        <th>Year</th>
                        <th>Semester</th>
                        <th>Section</th>
                        <th>Date</th>
                        <th>S.Y</th>
                        <th>GPA</th>
                        <th>Action</th>
                    </tr>
    
                    <?php if ($decoded != 'No archive exist') { ?>
                        <?php foreach($decoded as $obj): ?>
                            <tr>
                                <td><?php echo $obj->section ; ?></td>
                                <td><?php echo $obj->course ; ?></td>
                                <td><?php echo $obj->year ; ?></td>
                                <td><?php echo $obj->semester ; ?></td>
                                <td><?php echo $obj->section ; ?></td>
                                <td><?php echo $obj->date ; ?></td>
                                <td><?php echo $obj->school_year ; ?></td>
                                <td><?php echo $obj->gpa ; ?></td>
                                <td>
                                    <form action="application_history.php" method="POST">
                                        <input type="hidden" name="year" id="year" value="<?php echo $obj->year?>">
                                        <input type="hidden" name="semester" id="semester" value="<?php echo $obj->semester?>">
                                        <input type="hidden" name="school_year" id="school_year" value="<?php echo $obj->school_year?>">
                                        <input class="primary-btn" type="submit" name="view" id="view" value="View">
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php } ?>
                </table>
            </div>

        </div>
    </div>

    <script src="../assets/modal.js" type="text/javascript"></script>
</body>
</html>