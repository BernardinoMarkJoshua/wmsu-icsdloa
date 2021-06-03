<?php
    session_start();

    if ($_SESSION['STATUS'] != 'faculty') {
        echo '<script>window.location.href="../index.php"</script>';
    } else if ($_SESSION['STATUS'] == 'student') {
        echo '<script>window.location.href="student/student_landing.php"</script>';
    } else if ($_SESSION['SUBJECT_EDIT_STATUS'] != 'yes') {
        echo '<script>window.location.href="subject.php"</script>';
    } 
    $_SESSION['SUBJECT_EDIT_STATUS'] = 'no';
    $api = "http://icsdloa.online/cybersolution_backend/control/";

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

    if (isset($_POST['edit'])) {
        
        $subject_code_holder = $_SESSION['SUBJECT_EDIT_subject_code'];
        $course_name_holder = $_SESSION['SUBJECT_EDIT_course_name'];
        $subject_year_holder = $_SESSION['SUBJECT_EDIT_subject_year'];
        $subject_semester_holder = $_SESSION['SUBJECT_EDIT_subject_semester'];

        $subject_code = $_POST['subject_code'];
        $course_name = $_POST['course_name'];
        $subject_name = $_POST['subject_name'];
        $subject_year = $_POST['subject_year'];
        $subject_semester = $_POST['subject_semester'];
        $subject_units = $_POST['subject_units'];

        $ch = curl_init();
        $url = $api.'admin/editSubject.php';
        $post_data = array (
            "subject_code_holder" => $subject_code_holder,
            "course_name_holder" => $course_name_holder,
            "subject_year_holder" => $subject_year_holder,
            "subject_semester_holder" => $subject_semester_holder,
            "subject_code" => $subject_code,
            "course_name" => $course_name,
            "subject_name" => $subject_name,
            "subject_year" => $subject_year,
            "subject_semester" => $subject_semester,
            "subject_units" => $subject_units
        );
        $header = ['Content-Type: Text/plain'];

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        $output = curl_exec($ch);

        curl_close($ch);
        echo '<script>window.location.href="subject.php"</script>';
    }

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/style.css">
    <title>ICS-DLOA | Edit</title>
</head>
<body>
    
<div class="faculty-register-main-container">
        <div class="faculty-register-container">
            <div class="adviser-register">

                <div class="information">
                    <h2>ICS-DLOA Edit Subject</h2>
                    <p>
                        This Edit form is for the Subjects of courses of the Western Mindanao State University 
                        Institute of Computer Studies department.
                    </p>
                    <a class="cancel-btn" href="subject.php">Cancel Edit</a>
                </div>

                <form action="subjectEdit.php" method="POST" class="adviser-register-form">

                    <div class="name-reg-adviser">
                        <input type="text" name="subject_code" id="subject_code" placeholder="Subject Code" required>
                        <input type="text" name="subject_name" id="subject_name" placeholder="Subject Name" required>
                        <input type="text" name="subject_units" id="subject_units" placeholder="Subject Units" required>
                    </div>


                    <div class="bottom-reg-adviser">
                        <select name="course_name" id="course_name" required>
                            <option value="" disabled selected>Course Select </option>
                            <?php foreach($decoded2 as $obj): ?>
                                <option value="<?php echo $obj->curriculum_name?>"><?php echo $obj->curriculum_name?></option>
                            <?php endforeach; ?>
                        </select>

                        <select name="subject_year" id="subject_year" required>
                            <option value="" disabled selected>Year Select</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                        </select>

                        <select name="subject_semester" id="subject_semester" required>
                            <option value="" disabled selected>Semester Select</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                        </select>
                        
                    </div>

                    <div class="register-btn-container">
                        <input class='register-btn' type="submit" name="edit" id="edit" value="Save">
                    </div>

                    </div>
    
                </form>
            </div>

        </div>
    </div>

</body>
</html>