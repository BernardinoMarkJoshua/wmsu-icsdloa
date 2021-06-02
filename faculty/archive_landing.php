<?php
    session_start();

    if ($_SESSION['STATUS'] != 'faculty') {
        echo '<script>window.location.href="../index.php"</script>';
    } else if ($_SESSION['STATUS'] == 'student') {
        echo '<script>window.location.href="student/student_landing.php"</script>';
    } else if ($_SESSION['ADMIN_STATUS'] != 'yes') {
        echo '<script>window.location.href="facultyLanding.php"</script>';
    } 

    $api = "http://icsdloa.online/cybersolution_backend/control/";

    $ch = curl_init();
    $url = $api.'student/fetchCurriculum.php';

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $resp = curl_exec($ch);

    if ($e = curl_error($ch)) {
        echo $e;
    } else {
        $curriculum = json_decode($resp);
    } curl_close($ch);

    if (isset($_POST['search'])) {
        $school_year = $_POST['school_year'];
        $semester_post = $_POST['semester_post'];

        $ch = curl_init();

        $url = $api.'admin/viewArchive.php';
        $post_data = array (
            "school_year" => $school_year,
            "semester" => $semester_post
        );
        $header = ['Content-Type: Text/plain'];

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        $output = curl_exec($ch);
        $decoded = json_decode($output);

        curl_close($ch);

    } if (isset($_POST['logout'])) {
        session_destroy();
        echo "<script>window.location.href='../index.php'</script>";

    } if (isset($_POST['view_grade'])) {

        $_SESSION['ARCHIVE_VIEW_STATUS'] = 'yes';
        $_SESSION['STUDENT_ID_ARCHIVE_VIEW'] = $_POST['student_id'];
        $_SESSION['YEAR_ARCHIVE_VIEW'] = $_POST['year'];
        $_SESSION['SEMESTER_ARCHIVE_VIEW'] = $_POST['semester'];
        $_SESSION['SCHOOL_YEAR_ARCHIVE_VIEW'] = $_POST['school_year'];
        echo "<script>window.location.href='view_archive_grade.php'</script>";
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
            <a href="adminLanding.php">Statistics</a>
            
            <form action="archive_landing.php" method="POST">

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
            
            <form action="archive_landing.php" method="POST">
                    <select class="drop-down-search" name="school_year" id="school_year">
                        <option value="" disbled selected >Select school year</option>
                        <option value="2020-2021">2020-2021</option>
                        <option value="2021-2022">2021-2022</option>
                        <option value="2022-2023">2022-2023</option>
                        <option value="2023-2024">2023-2024</option>
                        <option value="2024-2025">2024-2025</option>
                        <option value="2025-2026">2025-2026</option>
                        <option value="2026-2027">2026-2027</option>
                        <option value="2027-2028">2027-2028</option>
                        <option value="2028-2029">2028-2029</option>
                        <option value="2029-2030">2029-2030</option>
                    </select>

                    <select class="drop-down-search" name="semester_post" id="semester_post">
                        <option value="" disbled selected >Select Semester</option>
                        <option value="1" >1st</option>
                        <option value="2">2nd</option>
                    </select>

                 

                    <input class="blue-btn" type="submit" name="search" id="search" value="Search">
                </form>

            <table  class="table_achievers">
                <tr>
                    <th>Name</th>
                    <th>Student id</th>
                    <th>Section</th>
                    <th>Course</th>
                    <th>Year</th>
                    <th>Semester</th>
                    <th>Date</th>
                    <th>GPA</th>
                    <th>School year</th>
                    <th>Action</th>
                </tr>

                <?php if (isset($decoded) && $decoded != 'No archive exist') { ?>
                    <?php foreach ($decoded as $obj): ?>
                        <tr class="student-table">
                                <td><?php echo $obj->lastname.', '.$obj->middlename.' '.$obj->firstname;?></td>
                                <td><?php echo $obj->student_id; ?></td>
                                <td><?php echo $obj->section; ?></td>
                                <td><?php echo $obj->course; ?></td>
                                <td><?php echo $obj->year; ?></td>
                                <td><?php echo $obj->semester; ?></td>
                                <td><?php echo $obj->date; ?></td>
                                <td><?php echo $obj->gpa; ?></td>
                                <td><?php echo $obj->school_year; ?></td>
                                <td>
                                    <form action="archive_landing.php" method="POST">
                                        <input type="hidden" name="student_id" value="<?php echo $obj->student_id; ?>">
                                        <input type="hidden" name="year" value="<?php echo $obj->year; ?>">
                                        <input type="hidden" name="semester" value="<?php echo $obj->semester; ?>">
                                        <input type="hidden" name="school_year" value="<?php echo $obj->school_year; ?>">
                                        <input type="submit" class="primary-btn" name="view_grade" id="view_grade" value="View">
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

