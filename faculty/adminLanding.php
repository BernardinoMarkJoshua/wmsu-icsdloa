<?php
    session_start();
    $api = "http://icsdloa.online/cybersolution_backend/control/";
    
    if ($_SESSION['STATUS'] != 'faculty') {
        echo '<script>window.location.href="../index.php"</script>';
    } else if ($_SESSION['STATUS'] == 'student') {
        echo '<script>window.location.href="student/student_landing.php"</script>';
    } else if ($_SESSION['ADMIN_STATUS'] != 'yes') {
        echo '<script>window.location.href="student/facultyLanding.php"</script>';
    } 
    
    $ch = curl_init();
    $url = $api.'admin/fetchDefaults.php';

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $resp = curl_exec($ch);

    if ($e = curl_error($ch)) {
        echo $e;
    } else {
        $defaults_decoded = json_decode($resp);
    } curl_close($ch);
    
    $ch = curl_init();
    $url = $api.'adviser/fetchStudents.php';

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $resp = curl_exec($ch);

    if ($e = curl_error($ch)) {
        echo $e;
    } else {
        $students = json_decode($resp);
    } curl_close($ch);
    
    $ch = curl_init();
    $url = $api.'admin/viewFaculty.php';

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $resp = curl_exec($ch);

    if ($e = curl_error($ch)) {
        echo $e;
    } else {
        $faculty = json_decode($resp);
    } curl_close($ch);
    
    
    $ch = curl_init();
    $url = $api.'admin/viewAchievers.php';

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $resp = curl_exec($ch);

    if ($e = curl_error($ch)) {
        echo $e;
    } else {
        $achievers = json_decode($resp);
    } curl_close($ch);
    
    $ch = curl_init();
    $url = $api.'admin/waitingStatus.php';

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $resp = curl_exec($ch);

    if ($e = curl_error($ch)) {
        echo $e;
    } else {
        $waiting = json_decode($resp);
    } curl_close($ch);
    
    $ch = curl_init();
    $url = $api.'admin/approvedStatus.php';

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $resp = curl_exec($ch);

    if ($e = curl_error($ch)) {
        echo $e;
    } else {
        $approved = json_decode($resp);
    } curl_close($ch);
    
    $counter_waiting = 0;
    $counter_approved = 0;
    $counter_achievers = 0;
    $counter_faculty = 0;
    $counter_students = 0;
    
    if (isset($waiting)) {
        foreach ($waiting as $obj) {
            $counter_waiting +=1;
        }
    }
    
    if (isset($approved)) {
        foreach ($approved as $obj2) {
            $counter_approved +=1;
        }
    }
    
    if (isset($achievers)) {
        foreach ($achievers as $obj3) {
            $counter_achievers +=1;
        }
    }
    
    if (isset($faculty)) {
        foreach ($faculty as $obj4) {
            $counter_faculty +=1;
        }
    }
    
    if (isset($students)) {
        foreach ($students as $obj4) {
            $counter_students +=1;
        }
    }
    
    $ongoing = $counter_waiting + $counter_approved;
    
    if (isset($_POST['logout'])) {
        session_destroy();
        echo '<script>window.location.href="../index.php"</script>';
    }
    
    foreach ($defaults_decoded as $def_obj) {
        $sy = $def_obj->school_year;
        $ad = $def_obj->archive_date;
        $ad2 = $def_obj->archive_date2;
        $sem = $def_obj->semester;
        $fin = $def_obj->finalizing;
        $app = $def_obj->application;
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
    <title>ICS-DLOA | Admin</title>
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
            <a href="achievers.php">View Achievers</a>
            <a href="faculty.php">Set Faculty</a>
            <a href="viewStudents.php">Set Students</a>
            <a href="subject.php">Set Subjects</a>
            <a href="archive_landing.php">View Archive</a>
            <a href="edit_misc.php">Date Settings</a>
            <a href="facultyLanding.php">Back to Role Select</a>
                
                <form action="adminLanding.php" method="POST">

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
            
            <div style="font-size: large; margin-top: 150px; display: flex; flex-direction: column; justify-content: left;">
            <span><?php echo 'Application forms in process: ' .$ongoing;?></span>
            <br/>
            <span><?php echo 'Current Achievers: ' .$counter_achievers;?></span>
            <br/>
            <span><?php echo 'Total Faculty Members: ' .$counter_faculty;?></span>
            <br/>
            <span><?php echo 'Total Students: ' .$counter_students;?></span>
            <br/>
            <span><?php echo 'Current School Year: ' .$sy;?></span>
            <br/>
            <span><?php echo '1st Semester Archive Date (M-D): ' .$ad;?></span>
            <br/>
            <span><?php echo '2nd Semester Archive Date (M-D): ' .$ad2;?></span>
            <br/>
            <span><?php echo 'Current Semester: ' .$sem;?></span>
            <br/>
            <span><?php echo 'Show Leaderboard: ' .$fin;?></span>
            <br/>
            <span><?php echo 'Allow Application: ' .$app;?></span>
            </div>
        </div>

       
    </div>
    
    
    <script src="../assets/modal.js" type="text/javascript"></script>
</body>
</html>
