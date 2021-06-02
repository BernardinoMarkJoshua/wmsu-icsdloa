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

    $style2 = '
        margin-top: 8px; 
        padding-left: 5px;
        background: rgb(220, 220, 220); 
        color: rgb(218, 218, 218); 
        height: 30px;
        display: flex;
        align-items: center;
    ';

    $ch = curl_init();
    $url = $api.'student/fetchAchievers.php';

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $resp = curl_exec($ch);

    if ($e = curl_error($ch)) {
        echo $e;
    } else {
        $decoded = json_decode($resp);
    } curl_close($ch);


    $ch = curl_init();
    $url = $api."student/checkWaitingForms.php";
    $post_data = array("student_id" => $user_id);
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


    $ch = curl_init();
    $url = $api."student/getDirectorState.php";
    $post_data = array ("student_id" => $user_id);
    $header = ['Content-type: Text/plain'];

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    $output = curl_exec($ch);
    $director_decoded = json_decode($output);


    $ch = curl_init();
    $url = $api.'student/fetchDefaults.php';

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $resp = curl_exec($ch);

    if ($e = curl_error($ch)) {
        echo $e;
    } else {
        $decoded1 = json_decode($resp);
    } curl_close($ch);

    foreach ($decoded1 as $obj) {
        $school_year = $obj->school_year;
        $semester = $obj->semester;
        $finalizing = $obj->finalizing;
        $application = $obj->application;
    }

    if ($semester == 1) {
        $semester = "1st";
    } else {
        $semester = "2nd";
    }



    if ($checkWaitingForms != 'form does not exist') {
        $style = 'display : none;';

        $style_waiting = '
            margin-top: 8px; 
            padding-left: 5px;
            background: green; 
            color: white; 
            height: 30px;
            display: flex;
            align-items: center;
        ';

        $style_committee = 'margin-top: 8px;padding-left: 5px;background: rgb(220, 220, 220);height: 30px;display: flex;align-items: center;';
        $style_director = 'margin-top: 8px;padding-left: 5px;background: rgb(220, 220, 220);height: 30px;display: flex;align-items: center;';
        $style_achiever = 'margin-top: 8px;padding-left: 5px;background: rgb(220, 220, 220);height: 30px;display: flex;align-items: center;';
        
        $span_waiting = 'white';
        $span_committee = 'color: black;';
        $span_director = 'color: black;';
        $span_achiever = 'color: black;';

        $_SESSION['SESSION_APPLY'] = 'no';
    } else if ($checkCurrentAchievers != 'form does not exist') {
        $style = 'display : none;';

        $style_waiting = 'margin-top: 8px;padding-left: 5px;background: rgb(150, 150, 150);height: 30px;display: flex;align-items: center;';
        $style_committee = 'margin-top: 8px;padding-left: 5px;background: rgb(150, 150, 150);height: 30px;display: flex;align-items: center;';
        $style_director = 'margin-top: 8px;padding-left: 5px;background: rgb(150, 150, 150);height: 30px;display: flex;align-items: center;';
        
        $span_waiting = 'color: black;';
        $span_committee = 'color: black;';
        $span_director = 'color: black;';
        $span_achiever = 'white;';

        $style_achiever = '
            margin-top: 8px; 
            padding-left: 5px;
            background: green; 
            color: white; 
            height: 30px;
            display: flex;
            align-items: center;
        ';

        $_SESSION['SESSION_APPLY'] = 'no';
    } else if ($director_decoded != 'no waiting') {
        $style = 'display : none;';

        $style_director = '
            margin-top: 8px; 
            padding-left: 5px;
            background: green; 
            color: white; 
            height: 30px;
            display: flex;
            align-items: center;
        ';
        
        $style_waiting = 'margin-top: 8px;padding-left: 5px;background: rgb(150, 150, 150);height: 30px;display: flex;align-items: center;';
        $style_committee = 'margin-top: 8px;padding-left: 5px;background: rgb(150, 150, 150);height: 30px;display: flex;align-items: center;';
        $style_achiever = 'margin-top: 8px;padding-left: 5px;background: rgb(220, 220, 220);height: 30px;display: flex;align-items: center;';
        
        $span_waiting = 'color: black;';
        $span_committee = 'color: black;';
        $span_director = 'color: white;';
        $span_achiever = 'color: black;';

        $_SESSION['SESSION_APPLY'] = 'no';
    } else if ($checkApprovedForms != 'form does not exist') {
        $style = 'display : none;';

        $style_committee = '
            margin-top: 8px; 
            padding-left: 5px;
            background: green; 
            color: white; 
            height: 30px;
            display: flex;
            align-items: center;
        ';
        
        $style_waiting = 'margin-top: 8px;padding-left: 5px;background: rgb(150, 150, 150);height: 30px;display: flex;align-items: center;';
        $style_director = 'margin-top: 8px;padding-left: 5px;background: rgb(220, 220, 220);height: 30px;display: flex;align-items: center;';
        $style_achiever = 'margin-top: 8px;padding-left: 5px;background: rgb(220, 220, 220);height: 30px;display: flex;align-items: center;';
        
        $span_waiting = 'color: black;';
        $span_committee = 'color: white;';
        $span_director = 'color: black;';
        $span_achiever = 'color: black;';

        $_SESSION['SESSION_APPLY'] = 'no';
    } else {
        $style = 'display : block; display: flex; align-items: center;';
        $process = 'No current application in process';

        $style_committee = 'margin-top: 8px;padding-left: 5px;background: rgb(220, 220, 220);height: 30px;display: flex;align-items: center;';
        $style_waiting = 'margin-top: 8px;padding-left: 5px;background: rgb(220, 220, 220);height: 30px;display: flex;align-items: center;';
        $style_director = 'margin-top: 8px;padding-left: 5px;background: rgb(220, 220, 220);height: 30px;display: flex;align-items: center;';
        $style_achiever = 'margin-top: 8px;padding-left: 5px;background: rgb(220, 220, 220);height: 30px;display: flex;align-items: center;';
        
        $span_waiting = 'color: grey;';
        $span_committee = 'color: grey;';
        $span_director = 'color: grey;';
        $span_achiever = 'color: grey;';

        $_SESSION['SESSION_APPLY'] = 'yes';
    }

    if ($application == 'no') {
        $style = 'display: none;';
        $_SESSION['SESSION_APPLICATION_APPLY'] = 'no';
    } else {
        $_SESSION['SESSION_APPLICATION_APPLY'] = 'yes';
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
    <title>ICS-DLOA | Student</title>
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

                <form action="student_landing.php" method="POST">

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
            <div class="current_progress">
                <h3>Application Progress: </h3>

                    <div class="progress_bar" style="<?php echo $style_waiting?>">
                        <span style="<?php echo $span_waiting?>">Application Form sent. Waiting for adviser's verification</span>
                    </div>

                    <div class="progress_bar" style="<?php echo $style_committee?>">
                        <span style="<?php echo $span_committee?>">Application Form verified. Waiting for committee's verification</span>
                    </div>

                    <div class="progress_bar" style="<?php echo $style_director?>">
                        <span style="<?php echo $span_director?>">Application Form approved. Waiting for Director's verification</span>
                    </div>

                    <div class="progress_bar" style="<?php echo $style_achiever?>">
                        <span style="<?php echo $span_achiever?>">Congratulations Achiever! You are now part of the ICS Director's List</span>
                    </div>
            </div>

            <?php if ($finalizing == 'yes') { ?>
                <div class="table-container">
                    <h1>Top 10 Achievers</h1>
                    <table class="table_achievers">
                        <tr>
                            <th>Rank</th>
                            <th>Student ID</th>
                            <th>Course</th>
                            <th>Section</th>
                            <th>Year</th>
                            <th>GPA</th>
                        </tr>
        
                        <?php if ($decoded != "no students found") { ?>
                            <?php foreach ($decoded as $obj): ?>
                                <tr class="student-table">
                                    <td><?php echo $counter+=1; ?></td>
                                    <td><?php echo $obj->student_id; ?></td>
                                    <td><?php echo $obj->course; ?></td>
                                    <td><?php echo $obj->section; ?></td>
                                    <td><?php echo $obj->year; ?></td>
                                    <td><?php echo $obj->gpa; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php } ?>
                        
                    </table>
                    <a class="link" href="achievers.php">View All Achievers</a>
                </div>
            <?php } ?>
        </div>
    </div>
    
    <script src="../assets/modal.js" type="text/javascript"></script>
</body>
</html>