<?php
    session_start();

    if ($_SESSION['STATUS'] != 'student') {
        echo '<script>window.location.href="../index.php"</script>';
    } else if ($_SESSION['STATUS'] == 'faculty') {
        echo '<script>window.location.href="faculty/facultyLanding.php"</script>';
    } else if ($_SESSION['SESSION_APPLY'] != 'yes') {
        echo '<script>window.location.href="student_landing.php"</script>';
    } else if ($_SESSION['SESSION_APPLICATION_APPLY'] != 'yes') {
        echo '<script>window.location.href="student_landing.php"</script>';
    }

    $api = "http://icsdloa.online/cybersolution_backend/control/";
    $toggle = false;
    $user_id = $_SESSION['STUDENT_ID'];
    $section = " ";
    $msg = " ";
    $modal_gpa = 0;
    $counter2 = 0;
    $counter3 = 0;
    $counter4 = 0;
    $style = 'color:black';

        $ch = curl_init();

        $url = $api."student/fetchStudentInfo.php";
        $post_data = array ("student_id" => $user_id);
        $header = ['Content-type: Text/plain'];

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        $output = curl_exec($ch);
        $decoded = json_decode($output);

        foreach ($decoded as $obj) {
            $student_college = $obj->college;
            $student_curriculum = $obj->curriculum;
            $student_course = $obj->course;
        } curl_close($ch);

        $ch = curl_init();

        $url = $api.'student/fetchAdvisers.php';

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $resp = curl_exec($ch);

        if ($e = curl_error($ch)) {
            echo $e;
        } else {
            $advisers = json_decode($resp);
        } curl_close($ch);


        $ch = curl_init();
        $url = $api.'student/fetchDefaults.php';
    
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
        $resp = curl_exec($ch);
    
        if ($e = curl_error($ch)) {
            echo $e;
        } else {
            $defaults_decoded = json_decode($resp);
        } curl_close($ch);

        foreach ($defaults_decoded as $defaults_obj) {
            $current_semester = $defaults_obj->semester;
        }

    if (isset($_POST['generate'])) {
        $toggle = 'display: block;';
        $year = $_POST['year_level'];
        $section = $_POST['section'];
        $_SESSION['APPLY_YEAR'] = $_POST['year_level'];
        $_SESSION['APPLY_SECTION'] = $_POST['section'];
        $_SESSION['APPLY_ADVISER'] = $_POST['adviser'];

        $ch = curl_init();

        $url = $api."student/fetchSubjects.php";
        $post_data = array (
            "year" => $year,
            "curriculum_name" => $student_curriculum
        );

        $header = ['Content-type: Text/plain'];

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        $output = curl_exec($ch);
        $decoded2 = json_decode($output);
        curl_close($ch);

    } else if (isset($_POST['apply'])) {
        $units_sum = 0;
        $sum = 0;
        $apply_section = $_SESSION['APPLY_SECTION'];
        $year = $_SESSION['APPLY_YEAR'];
        $apply_adviser = $_SESSION['APPLY_ADVISER'];
        $total_gpa = $_SESSION['STUDENT_GPA_APPLY'];
        $array_grades_apply = $_SESSION['ARRAY_GRADES_APPLY'];
        $array_subject_codes_apply = $_SESSION['SUBJECT_CODES_APPLY'];

        if ($total_gpa <= 2.0){
            foreach ( $array_grades_apply as $array_grades_obj) {
                // $subject_code = $_POST['subject_code'.$x];
                
                $subject_code = $array_subject_codes_apply[$counter2];
                $counter2+= 1;

                $grade = $array_grades_obj;

                $ch = curl_init();

                $url = $api."student/sendGrades.php";
                $post_data = array (
                    "student_id" => $user_id,
                    "subject_code" => $subject_code,
                    "grade" => $grade,
                    "year" => $year
                );

                $header = ['Content-type: Text/plain'];

                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
                curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
                $output = curl_exec($ch);
                curl_close($ch);
            }

            $ch = curl_init();

            $url = $api."student/sendAppform.php";
            $post_data = array (
                "student_id" => $user_id,
                "section" => $apply_section,
                "course" => $student_course,
                "year" => $year,
                "gpa" => $total_gpa,
                "adviser" => $apply_adviser
            );

            $header = ['Content-type: Text/plain'];

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            $output = curl_exec($ch);
            curl_close($ch);
            $_SESSION['APPLY_MESSAGE'] = 'you have applied for dean\'s list please wait for an email regarding the acceptance of your application form'; 
            echo '<script>window.location.href="student_landing.php"</script>';

        } else {
            $msg = "sorry your GPA did not meet the required grade to apply for dean's list";
        }
    }
    $counter = 0;

    if (isset($_POST['logout'])) {
        session_destroy();
        echo "<script>window.location.href='../index.php'</script>";

    } if (isset($_POST['modal'])) {
        $units_sum = 0;
        $sum = 0;
        
        for ($x = 1; $x <= $_POST['counter']; $x++) {
            
            $grade_array = $_POST['grade'.$x];
            $subject_code_array = $_POST['subject_code'.$x];
            $subject_units_array = $_POST['subject_units'.$x];

            $array_grades[] = $grade_array;
            $subject_codes_holder[] = $subject_code_array;
            $subject_units_holder[] = $subject_units_array;
         }

         for ($x = 1; $x <= $_POST['counter']; $x++) {
            $units_sum = $units_sum + $_POST['subject_units'.$x];
            $grade = $_POST['grade'.$x];
            $product = $_POST['subject_units'.$x] * $grade;
            $sum = $sum + $product;
         }

         $student_gpa = $sum/$units_sum;

         $_SESSION['COUNTER_APPLY'] = $_POST['counter'];
         $_SESSION['ARRAY_GRADES_APPLY'] = $array_grades;
         $_SESSION['SUBJECT_CODES_APPLY'] = $subject_codes_holder;
         $_SESSION['SUBJECT_UNITS_APPLY'] = $subject_units_holder;
         $_SESSION['STUDENT_GPA_APPLY'] = $student_gpa;

         if ($student_gpa <= 2.0) {
            $style = 'color:green';
         } else {
            $style = 'color:red';
         }

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
    
    <title>ICS-DLOA | Apply</title>
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
                <p><?php echo $_SESSION['FIRSTNAME_STUDENT'].' '.$_SESSION['MIDDLENAME_STUDENT'].' '.$_SESSION['LASTNAME_STUDENT']?></p>
            </div>

            <div class="navigation">
                <a href="studentApply.php">Apply</a>
                <a href="change_password.php">Change Password</a>
                <a href="application_history.php">View Past Records</a>
                
                <form action="studentApply.php" method="POST">

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
                <a href="student_landing.php">Back</a>
            </div>

            <div class="apply_form">
                <h2>Apply</h2>
                <span>please select Curriculum to generate grades and apply</span>
                <form action="studentApply.php" method="POST">

                    <input class="primary-txt" type="hidden" name="semester" id="semester">

                    <select class="primary-txt" name="year_level" id="year_level" required>
                        <option value="" disabled selected>Select Year Level</option>
                        <option value="1">1st</option>
                        <option value="2">2nd</option>
                        <option value="3">3rd</option>
                        <option value="4">4th</option>
                    </select>

                    <select class="primary-txt" name="section" id="section" required>
                        <option value="" disabled selected>Select Section</option>
                        <option value="A">Section A</option>
                        <option value="B">Section B</option>
                        <option value="C">Section C</option>
                        <option value="D">Section D</option>
                    </select>

                    <select class="primary-txt" name="adviser" id="adviser" required>
                        <option value="" disabled selected>Select Adviser</option>
                        <?php foreach ($advisers as $obj_advisers): ?>
                            <option value="<?php echo $obj_advisers->name?>"><?php echo $obj_advisers->name?></option>
                        <?php endforeach; ?>
                    </select>

                    <input class="primary-btn" type="submit" name="generate" id="generate" value="Generate">
                </form>
            </div>
            <span style="color:red;"><?php echo $msg?></span>

            <?php if ($toggle != FALSE) { ?>

                <span style="margin-top: 20px;"><strong>Section: </strong><?php echo $_SESSION['APPLY_SECTION'].'<br/><strong>Year:</strong> '. $year.'<br/><strong>Adviser:</strong> '.$_SESSION['APPLY_ADVISER'].'<br/><strong>Course:</strong> '.$student_course.'<br/><strong>Semester:</strong> '.$current_semester?></span>
            <div class="table-container">

                <table class="table_achievers">
                    <tr>
                        <th>Subject Code</th>
                        <th>Subject Name</th>
                        <th>Subject Units</th>
                        <th>Grade Input</th>
                    </tr>

                    <?php foreach ($decoded2 as $obj2):?>
                        <tr>
                            <td><?php echo $obj2->subject_code;?></td>
                            <td><?php echo $obj2->subject_name;?></td>
                            <td><?php echo $obj2->subject_units;?></td>
                            <td>
                                <form action="studentApply.php" method="POST">
                                    <input type="hidden" name="counter" id="counter" value="<?php echo $counter+=1?>">  
                                    <input type="hidden" name="subject_code<?php echo $counter?>" id="subject_code<?php echo $counter?>" value="<?php echo $obj2->subject_code?>">
                                    <input type="hidden" name="subject_units<?php echo $counter?>" id="subject_units<?php echo $counter?>" value="<?php echo $obj2->subject_units?>">
                                    
                                    <select name="grade<?php echo $counter?>" id="grade<?php echo $counter?>" required>
                                        <option value="" disabled selected>Select Grade</option>
                                        <option value="1.0">1.0</option>
                                        <option value="1.25">1.25</option>
                                        <option value="1.5">1.5</option>
                                        <option value="1.75">1.75</option>
                                        <option value="2.0">2.0</option>
                                        <option value="2.25">2.25</option>
                                        <option value="2.5">2.5</option>
                                        <option value="2.75">2.75</option>
                                        <option value="3.0">3.0</option>
                                    </select>
                            </td>
                        
                        </tr>

                    <?php endforeach; ?>
                        
                </table>
                                    
                                    <input type="submit" name="modal" value="Apply" class="modal-open">
                                </form>
            </div>
            <?php } ?>


                    <div class="modal" id="modal">
                        <div class="modal__content">
                        <a href="#" class="modal__close">&times;</a>
                        <h2 class="modal__heading">Confirm</h2>
                            <span>Continue to approve student registration?</span>
                            <form action="studentApply.php" method="POST">
                                <input type="hidden" name="counter" value="<?php echo $_SESSION['COUNTER_APPLY']?>">
                                <span style="<?php echo $style?>">Your GPA is: <?php echo $_SESSION['STUDENT_GPA_APPLY'];?></span>
                                <input type="submit" class="blue-btn" value="Cancel">
                                <input class="primary-btn" type="submit" name="apply" id="apply" value="Apply">
                            </form>
                        </div>
                    </div>
            

        </div>
    </div>

    <script src="../assets/modal.js" type="text/javascript"></script>
</body>
</html>





