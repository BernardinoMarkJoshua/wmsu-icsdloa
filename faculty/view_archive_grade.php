<?php
    session_start();

    if ($_SESSION['STATUS'] != 'faculty') {
        echo '<script>window.location.href="../index.php"</script>';
    } else if ($_SESSION['STATUS'] == 'student') {
        echo '<script>window.location.href="student/student_landing.php"</script>';
    } else if ($_SESSION['ADMIN_STATUS'] != 'yes') {
        echo '<script>window.location.href="facultyLanding.php"</script>';
    } else if ($_SESSION['ARCHIVE_VIEW_STATUS'] != 'yes') {
        echo '<script>window.location.href="archive_landing.php"</script>';
    } 

    $api = "http://icsdloa.online/cybersolution_backend/control/";

    $_SESSION['ARCHIVE_VIEW_STATUS'] = 'no';
    $user_id = $_SESSION['STUDENT_ID_ARCHIVE_VIEW'];
    $year = $_SESSION['YEAR_ARCHIVE_VIEW'];
    $semester = $_SESSION['SEMESTER_ARCHIVE_VIEW'];
    $school_year = $_SESSION['SCHOOL_YEAR_ARCHIVE_VIEW'];
    
    $ch = curl_init();
    $url = $api.'student/fetchStudentHistoryGrades.php';
    
    $post_data = array (
        "student_id" => $user_id,
        "year" => $year,
        "semester" => $semester,
        "school_year" => $school_year
    );
    $header = ['Content-type: Text/plain'];

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    $output = curl_exec($ch);
    $decoded = json_decode($output);

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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/modal2.css">
    <link rel="stylesheet" href="../assets/modal.css">
    <link rel="stylesheet" href="../assets/style.css">
    <title>ICS-DLOA | Archive</title>
</head>
<body>

<div class="faculty_container">

    <div class="side_panel">

        <div class="ics_dloa">
            <img class="ics_logo" src="../assets/images/icslogo.png" alt="ics_logo">
            <div>
                <span><strong>Western Mindanao State University</strong></span>
                <h4>ICS-DLOA</h4>
                <span>Faculty Mmeber</span>
            </div>
        </div>

        <div class="name_container">
            <span>Welcome</span>
            <p><?php echo $_SESSION['USERNAME'];?></p>
        </div>

        <div class="navigation">
            <a href="achievers.php">Achievers</a>
            <a href="faculty.php">Faculty</a>
            <a href="viewStudents.php">Students</a>
            <a href="subject.php">Subjects</a>
            <a href="archive_landing.php">Archive</a>
            <a href="edit_misc.php">Edit Misc</a>
            <a href="facultyLanding.php">Role Select</a>
            
            <form action="view_archive_grade.php" method="POST">

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
            <h1>Archive</h1>
            <a href="archive_landing.php">Back</a>
                <table class="table_achievers">
                    <tr>
                        <th>Subject Code</th>
                        <th>Subject Name</th>
                        <th>Subject Units</th>
                        <th>Grade</th>
                    </tr>
    
                    <?php foreach($decoded as $obj): ?>
                        <tr>
                            <td><?php echo $obj->subject_code ; ?></td>
                            <td><?php echo $obj->subject_name ; ?></td>
                            <td><?php echo $obj->subject_units ; ?></td>
                            <td><?php echo $obj->grade ; ?></td>   
                        </tr>
                    <?php endforeach; ?>
                </table>
        </div>
</div>

<script src="../assets/modal.js" type="text/javascript"></script>

</body>
</html>

