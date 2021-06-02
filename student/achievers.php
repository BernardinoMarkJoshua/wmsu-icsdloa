<?php  
    session_start();
    
    if ($_SESSION['STATUS'] != 'student') {
        echo '<script>window.location.href="../index.php"</script>';
    } else if ($_SESSION['STATUS'] == 'faculty') {
        echo '<script>window.location.href="faculty/facultyLanding.php"</script>';
    }

    $api = "http://icsdloa.online/cybersolution_backend/control/";
    $ch = curl_init();
    $url = $api.'student/fetchAllAchievers.php';

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $resp = curl_exec($ch);

    if ($e = curl_error($ch)) {
        echo $e;
    } else {
        $decoded = json_decode($resp);
    } curl_close($ch);

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
                <a href="studentApply.php">Apply</a>
                <a href="change_password.php">Change Password</a>
                <a href="application_history.php">View Past Records</a>
                
                <form action="achievers.php" method="POST">

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
                <h1>Current Achievers</h1>
                <span>Congratulations! The Institute of Computer Science is very proud of you</span>
                <h5>S.Y <?php echo $school_year?></h5>
                
                <table class="table_achievers">
                    <tr>
                        <th>#</th>
                        <th>Student ID</th>
                        <th>Course</th>
                        <th>Section</th>
                        <th>Year</th>
                        <th>GPA</th>
                    </tr>

                    <?php if ($decoded != 'no students found') { ?>
                        <?php $counter=0; foreach ($decoded as $obj): ?>
                            <tr>
                                <td><?php echo $counter+=1; ?></td>
                                <td><?php echo $obj->student_id ?></td>
                                <td><?php echo $obj->course; ?></td>
                                <td><?php echo $obj->section; ?></td>
                                <td><?php echo $obj->year; ?></td>
                                <td><?php echo $obj->gpa; ?></td>
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