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
    $style = "visibility: hidden;";
    $style1 = "visibility: hidden;";
    
    $ch = curl_init();
    $url = $api.'student/fetchCurriculum.php';

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $resp = curl_exec($ch);

    if ($e = curl_error($ch)) {
        echo $e;
    } else {
        $decoded2 = json_decode($resp);
    } curl_close($ch);

    if (isset($_SESSION['SUBJECT_course_name'])) {

        $course_name = $_SESSION['SUBJECT_course_name'];
        $subject_year = $_SESSION['SUBJECT_subject_year'];
        $subject_semester = $_SESSION['SUBJECT_subject_semester'];
        $style = "visibility: visible;";
        
        $ch = curl_init();
        $url = $api.'admin/viewSubjects.php';
    
        $post_data = array (
            "course_name" => $course_name,
            "subject_year" => $subject_year,
            "subject_semester" => $subject_semester,
        );
    
        $header = ['Content-Type: Text/plain'];
    
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        $output = curl_exec($ch);
        $decoded1 = json_decode($output);

        curl_close($ch);
    }
    
    if (isset($_POST['search'])) {
        $subject_curriculum = $_POST['course_name'];
        $subject_year = $_POST['subject_year'];
        $subject_semester = $_POST['subject_semester'];

        $_SESSION['SUBJECT_course_name'] = $_POST['course_name'];
        $_SESSION['SUBJECT_subject_year'] = $_POST['subject_year'];
        $_SESSION['SUBJECT_subject_semester'] = $_POST['subject_semester'];
        
        $style = "visibility: visible;";

        $ch = curl_init();
        $url = $api.'admin/viewSubjects.php';
    
        $post_data = array (
            "course_name" => $subject_curriculum,
            "subject_year" => $subject_year,
            "subject_semester" => $subject_semester,
        );
    
        $header = ['Content-Type: Text/plain'];
    
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        $output = curl_exec($ch);
        $decoded1 = json_decode($output);
        curl_close($ch);

    } else if (isset($_POST['add_subject'])) {
        $_SESSION['SUBJECT_ADD_STATUS'] = 'yes';
        $_SESSION['SUBJECT_ADD_course_name'] = $course_name;
        $_SESSION['SUBJECT_ADD_subject_year'] = $subject_year;
        $_SESSION['SUBJECT_ADD_subject_semester'] = $subject_semester;        
        echo '<script>window.location.href="subjectAdd.php"</script>';

    } else if (isset($_POST['remove'])) {
        $subject_code_remove = $_POST['subject_code_remove'];
        $course_name_remove = $_POST['course_name_remove'];
        $subject_year_remove = $_POST['subject_year_remove'];
        $subject_semester_remove = $_POST['subject_semester_remove'];

        $ch = curl_init();

        $url = $api.'admin/removeSubject.php';
        $post_data = array (
            "subject_code" => $subject_code_remove,
            "course_name" => $course_name_remove,
            "subject_year" => $subject_year_remove,
            "subject_semester" => $subject_semester_remove
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
        echo '<script>window.location.href="subject.php"</script>';

    } else if (isset($_POST['edit'])) {
        $_SESSION['SUBJECT_EDIT_STATUS'] = 'yes';
        $_SESSION['SUBJECT_EDIT_subject_code'] = $_POST['subject_code_remove'];
        $_SESSION['SUBJECT_EDIT_course_name'] = $_POST['course_name_remove'];
        $_SESSION['SUBJECT_EDIT_subject_year'] = $_POST['subject_year_remove'];
        $_SESSION['SUBJECT_EDIT_subject_semester'] = $_POST['subject_semester_remove'];
        echo '<script>window.location.href="subjectEdit.php"</script>';

    } else if (isset($_POST['add_curriculum'])) {
        $style1 = "visibility: visible; margin-bottom: 20px;";

    } else if (isset($_POST['save_curriculum'])) {
        $style1 = "visibility: hidden;";
        $curriculum_name = $_POST['course_curriculum'].' '.$_POST['effective_year'];
        $course_curriculum = $_POST['course_curriculum'];

        $ch = curl_init();
        $url = $api.'admin/addCurriculum.php';
    
        $post_data = array (
            "course_name" => $curriculum_name,
            "course_name_curriculum" => $course_curriculum
        );
    
        $header = ['Content-Type: Text/plain'];
    
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        $output = curl_exec($ch);
        $add_curriculum = json_decode($output);

        curl_close($ch);
        echo '<script>window.location.href="subject.php"</script>';

    } if (isset($_POST['modal'])) {
        $_SESSION['REMOVE_SUBJECT_CODE'] = $_POST['subject_code_remove'];
        $_SESSION['REMOVE_SUBJECT_CURRICULUM'] = $_POST['course_name_remove'];
        $_SESSION['REMOVE_SUBJECT_YEAR'] = $_POST['subject_year_remove'];
        $_SESSION['REMOVE_SUBJECT_SEMESTER'] = $_POST['subject_semester_remove'];

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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/modal2.css">
    <link rel="stylesheet" href="../assets/modal.css">
    <link rel="stylesheet" href="../assets/style.css">
    <title>ICS-DLOA | Subjects</title>
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
            
            <form action="subject.php" method="POST">

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
            <h1>Subjects</h1>
                <form action="subject.php" method="POST">
                    <select class="drop-down-search" name="course_name" id="course_name" required>
                        <option value="" disabled selected>Curriculum Select</option>
                        <?php foreach ($decoded2 as $obj2): ?>
                            <option value="<?php echo $obj2->curriculum_name?>"><?php echo $obj2->curriculum_name?></option>
                        <?php endforeach; ?>
                    </select>

                    <select class="drop-down-search" name="subject_year" id="subject_year" required>
                        <option value="" disabled selected>Year Select</option>
                        <option value="1">1st year level</option>
                        <option value="2">2nd year level</option>
                        <option value="3">3rd year level</option>
                        <option value="4">4th year level</option>
                    </select>

                    <select class="drop-down-search" name="subject_semester" id="subject_semester" required>
                        <option value="" disabled selected>Semester Select</option>
                        <option value="1">1st semester</option>
                        <option value="2">2nd semester</option>
                    </select>
                    
                    <input class="blue-btn" type="submit" name="search" id="search" value="Search"> 
                </form>

                <form action="subject.php" method="POST">               
                    <input class="secondary-btn" type="submit" name="add_curriculum" id="add_curriculum" value="Add Curriculum"> 
                </form>

                <form action="subject.php" method="POST" style="<?php echo $style1; ?>">

                    <select class="drop-down-search" name="course_curriculum" required>
                            <option value="" disabled selected>Course Select</option>
                            <option value="BSCS">BSCS</option>
                            <option value="BSIT">BSIT</option>
                    </select>

                    <select class="drop-down-search" name="effective_year" required>
                            <option value="" disabled selected>Effective Year Select</option>
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
                            <option value="2030-2031">2030-2031</option>
                            <option value="2031-2032">2031-2032</option>
                            <option value="2032-2033">2032-2033</option>
                            <option value="2033-2034">2033-2034</option>
                            <option value="2034-2035">2034-2035</option>
                            <option value="2035-2036">2035-2036</option>
                            <option value="2036-2037">2036-2037</option>
                            <option value="2037-2038">2037-2038</option>
                            <option value="2038-2039">2038-2039</option>
                            <option value="2039-2040">2039-2040</option>
                    </select>

                    <input class="blue-btn" type="submit" name="save_curriculum" id="save_curriculum" value="Save"> 
                </form>
                    
                
                <?php if (isset($_SESSION['SUBJECT_course_name'])) {?>
                    <span class="span-subjects"> 
                        <strong>Course and Semester: </strong><?php echo $_SESSION['SUBJECT_course_name'];?> <br/>
                        <strong>Year Level: </strong><?php echo $_SESSION['SUBJECT_subject_year'];?> <br/>
                        <strong>Semester: </strong><?php echo $_SESSION['SUBJECT_subject_semester'];?> <br/>
                    </span>
                <?php } ?>

                <table  class="table_achievers">
                <tr>
                    <th>Subject Code</th>
                    <th>Subject Name</th>
                    <th>Units</th>
                    <th>Actions</th>
                </tr>

                    <?php if (isset($decoded1) && $decoded1 != "Subjects do not exist") { ?>
                        <?php foreach ($decoded1 as $obj):?>
                            <tr>
                                <td><?php echo $obj->subject_code; ?></td>
                                <td><?php echo $obj->subject_name; ?></td>
                                <td><?php echo $obj->subject_units; ?></td>
                                <td>
                                    <form action="subject.php" method="POST">
                                        <input type="hidden" name="subject_code_remove" id="subject_code_remove" value="<?php echo $obj->subject_code; ?>">
                                        <input type="hidden" name="course_name_remove" id="course_name_remove" value="<?php echo $obj->subject_curriculum; ?>">
                                        <input type="hidden" name="subject_year_remove" id="subject_year_remove" value="<?php echo $obj->subject_year; ?>">
                                        <input type="hidden" name="subject_semester_remove" id="subject_semester_remove" value="<?php echo $obj->subject_semester; ?>" >

                                        <input class='primary-btn' type="submit" name="edit" id="edit" value='Edit'>
                                        <input type="submit" name="modal" value="Remove" class="modal-open2">
                                        
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php } ?>

                </table>
            
                <form action="subject.php" method="POST">
                    <input class="primary-btn" type="submit" name="add_subject" id="add_subject" value="Add Subject" style="<?php echo $style; ?>">
                </form>
                    
        </div>

                    <div class="modal" id="modal">
                        <div class="modal__content">
                        <a href="#" class="modal__close">&times;</a>
                        <h2 class="modal__heading">Confirm</h2>
                            <span>Contrinue to remove this subject?</span>
                            <form action="subject.php" method="POST">
                                <input type="hidden" name="subject_code_remove" value="<?php echo $_SESSION['REMOVE_SUBJECT_CODE']?>">
                                <input type="hidden" name="course_name_remove" value="<?php echo $_SESSION['REMOVE_SUBJECT_CURRICULUM']?>">
                                <input type="hidden" name="subject_year_remove" value="<?php echo $_SESSION['REMOVE_SUBJECT_YEAR']?>">
                                <input type="hidden" name="subject_semester_remove" value="<?php echo $_SESSION['REMOVE_SUBJECT_SEMESTER']?>">

                                <input type="submit" class="blue-btn" value="Cancel">
                                <input type="submit" class="primary-btn" name="remove" value="Continue">
                            </form>
                        </div>
                    </div>
            
    </div>
</div>

<script src="../assets/modal.js" type="text/javascript"></script>

</body>
</html>
